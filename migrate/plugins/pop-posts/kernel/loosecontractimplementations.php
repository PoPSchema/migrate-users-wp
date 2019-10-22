<?php
namespace PoP\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\Contracts\NameResolverFacade;
use PoP\LooseContracts\Facades\Contracts\LooseContractManagerFacade;
use PoP\LooseContracts\Contracts\AbstractLooseContractResolutionSet;

class PostsCMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts()
    {
		$this->nameResolver->implementNames([
			'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
		]);
	}
}

/**
 * Initialize
 */
new PostsCMSLooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	HooksAPIFacade::getInstance()
);

