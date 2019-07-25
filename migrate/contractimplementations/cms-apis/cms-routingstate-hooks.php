<?php
namespace PoP\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

class WPCMSRoutingStateHooks
{
    public function __construct() {

        HooksAPIFacade::getInstance()->addFilter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );
    }

    public function getNature($nature, $query)
    {
        if ($query->is_author()) {
            return POP_NATURE_USER;
        }

        return $nature;
    }
}

/**
 * Initialize
 */
new WPCMSRoutingStateHooks();
