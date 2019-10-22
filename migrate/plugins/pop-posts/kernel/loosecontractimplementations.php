<?php
namespace PoP\Users\WP;
use PoP\LooseContracts\Facades\Contracts\NameResolverFacade;

class PostsCMSLooseContractImplementations
{
	function __construct() {
		
		$nameresolver = NameResolverFacade::getInstance();
		$nameresolver->implementNames([
			'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
		]);
	}
}

/**
 * Initialize
 */
new PostsCMSLooseContractImplementations();

