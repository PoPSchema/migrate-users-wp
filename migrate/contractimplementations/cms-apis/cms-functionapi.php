<?php
namespace PoP\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

class FunctionAPI extends \PoP\Users\FunctionAPI_Base
{
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
            
            $query['number'] = $query['limit'];
            unset($query['limit']);
        }

        $query = HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:users:query',
            $query,
            $options
        );
        return get_users($query);
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
