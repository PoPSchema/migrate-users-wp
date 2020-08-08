<?php
namespace PoPSchema\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Users\Routing\RouteNatures;

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
            return RouteNatures::USER;
        }

        return $nature;
    }
}

/**
 * Initialize
 */
new WPCMSRoutingStateHooks();
