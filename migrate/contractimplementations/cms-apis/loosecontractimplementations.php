<?php
namespace PoP\Users\WP;

class CMSLooseContractImplementations
{
	function __construct() {
		
		$nameresolver = \PoP\LooseContracts\NameResolverFactory::getInstance();
		$nameresolver->implementNames([
			'popcms:dbcolumn:orderby:users:name' => 'name',
			'popcms:dbcolumn:orderby:users:id' => 'ID',
			'popcms:dbcolumn:orderby:users:registrationdate' => 'registered',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations();

