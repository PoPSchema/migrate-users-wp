<?php
namespace PoP\Users\WP;

class PostsCMSLooseContractImplementations
{
	function __construct() {
		
		$nameresolver = \PoP\LooseContracts\NameResolverFactory::getInstance();
		$nameresolver->implementNames([
			'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
		]);
	}
}

/**
 * Initialize
 */
new PostsCMSLooseContractImplementations();

