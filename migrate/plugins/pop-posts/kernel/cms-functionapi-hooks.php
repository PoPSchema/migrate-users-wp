<?php
namespace PoP\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

class FunctionAPIHooks {

	public function __construct() {
	
		HooksAPIFacade::getInstance()->addFilter(
		    'CMSAPI:posts:query',
		    [$this, 'convertPostsQuery'],
		    10,
		    2
		);
	}

	public function convertPostsQuery($query, array $options)
    {
        if (isset($query['authors'])) {

            $query['author'] = implode(',', $query['authors']);
            unset($query['authors']);
        }
        
        return $query;
    }
}

/**
 * Initialize
 */
new FunctionAPIHooks();
