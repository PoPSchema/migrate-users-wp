<?php
namespace PoP\Users\WP;
use PoP\LooseContracts\Facades\Contracts\NameResolverFacade;

class CMSLooseContractImplementations
{
	function __construct() {
		
		$nameresolver = NameResolverFacade::getInstance();
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

