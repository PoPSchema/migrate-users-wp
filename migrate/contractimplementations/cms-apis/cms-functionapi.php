<?php

namespace PoP\Users\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoP\Users\ComponentConfiguration;

class FunctionAPI extends \PoP\Users\FunctionAPI_Base
{
    use APITypeDataResolverTrait;

    public function getAuthorBase()
    {
        global $wp_rewrite;
        return $wp_rewrite->author_base;
    }
    public function getUserById($value)
    {
        return get_user_by('id', $value);
    }
    public function getUserByEmail($value)
    {
        return get_user_by('email', $value);
    }
    public function getUserBySlug($value)
    {
        return get_user_by('slug', $value);
    }
    public function getUserByLogin($value)
    {
        return get_user_by('login', $value);
    }
    public function getUsers($query = array(), array $options = [])
    {
        if ($return_type = $options['return-type']) {
            if ($return_type == POP_RETURNTYPE_IDS) {
                $query['fields'] = 'ID';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        // Convert parameters
        if (isset($query['name'])) {
            $query['meta_query'][] = [
                'key' => 'nickname',
                'value' => $query['name'],
                'compare' => 'LIKE',
            ];
            unset($query['name']);
        }
        if ($query['include']) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude'])) {
            // Transform from array to string
            $query['exclude'] = implode(',', $query['exclude']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            // Check if the limit is higher than the max limit
            $limit = $query['limit'];
            $maxLimit = ComponentConfiguration::getUserListMaxLimit();
            if (!is_null($maxLimit) && $maxLimit != -1 && ($limit == -1 || $limit > $maxLimit)) {
                $limit = $maxLimit;
            }
            // Assign the limit as the required attribute
            $query['number'] = $limit;
            unset($query['limit']);
        }
        // Limit users which have an email appearing on the input
        // WordPress does not allow to search by many email addresses, only 1!
        // Then we implement a hack to allow for it:
        // 1. Set field "search", as expected
        // 2. Add a hook which will modify the SQL query
        // 3. Execute query
        // 4. Remove hook
        $filterByEmails = false;
        if (isset($query['emails'])) {
            $emails = $query['emails'];
            // This works for either 1 or many emails
            $query['search'] = implode(',', $emails);
            // But if there's more than 1 email, we must modify the SQL query with a hook
            if (count($emails) > 1) {
                add_action('pre_user_query', [$this, 'enableMultipleEmails']);
                $filterByEmails = true;
            }
        }

        $query = HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:users:query',
            $query,
            $options
        );
        $ret = get_users($query);
        // Remove the hook
        if ($filterByEmails) {
            remove_action('pre_user_query', [$this, 'enableMultipleEmails']);
        }
        return $ret;
    }
    /**
     * Modify the SQL query, replacing searching for a single email
     * (with SQL operation "=") to multiple ones (with SQL operation "IN")
     *
     * @param [type] $query
     * @return void
     */
    public function enableMultipleEmails($query)
    {
        $qv =& $query->query_vars;
        if (isset($qv['search'])) {
            $search = trim($qv['search']);
            // Validate it has no wildcards, it's email (because there's a "@")
            // and there's more than one (because there's ",")
            $leading_wild = (ltrim($search, '*') != $search);
            $trailing_wild = (rtrim($search, '*') != $search);
            if (!$leading_wild && !$trailing_wild && false !== strpos($search, '@') && false !== strpos($search, ',')) {
                // Replace the query
                $emails = explode(',', $search);
                $searches = [sprintf("user_email IN (%s)", "'" . implode("','", $emails) . "'")];
                $replace = $query->get_search_sql($search, ['user_email'], false);
                $replacement = ' AND (' . implode(' OR ', $searches) . ')';
                $query->query_where = str_replace($replace, $replacement, $query->query_where);
            }
        }
    }
    public function getUserDisplayName($user_id)
    {
        return get_the_author_meta('display_name', $user_id);
    }
    public function getUserEmail($user_id)
    {
        return get_the_author_meta('user_email', $user_id);
    }
    public function getUserFirstname($user_id)
    {
        return get_the_author_meta('user_firstname', $user_id);
    }
    public function getUserLastname($user_id)
    {
        return get_the_author_meta('user_lastname', $user_id);
    }
    public function getUserLogin($user_id)
    {
        return get_the_author_meta('user_login', $user_id);
    }
    public function getUserDescription($user_id)
    {
        return get_the_author_meta('description', $user_id);
    }
    public function getUserRegistrationDate($user_id)
    {
        return get_the_author_meta('user_registered', $user_id);
    }
    public function getUserURL($user_id)
    {
        return get_author_posts_url($user_id);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
