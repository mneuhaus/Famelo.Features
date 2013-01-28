<?php
namespace Famelo\Features;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 */
class FeatureService {
	/**
	 * The featureService
	 *
	 * @var \Famelo\Features\Domain\Repository\FeatureRepository
	 * @Flow\Inject
	 */
	protected $featureRepository;

	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	public function isFeatureActive($feature) {
		$feature = $this->featureRepository->findOneByTitle($feature);
		$account = $this->securityContext->getAccount();
		$roles = array();
		foreach ($this->securityContext->getRoles() as $role) {
			$roles[] = strval($role);
		}
		if ($feature !== NULL) {
			foreach ($feature->getToggles() as $toggle) {
				if ($toggle->getGlobal() == TRUE) {
					return TRUE;
				}
				if ($account !== NULL && $toggle->getAccount() == $account) {
					return TRUE;
				}
				if (in_array($toggle->getRole(), $roles)) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}
}
?>