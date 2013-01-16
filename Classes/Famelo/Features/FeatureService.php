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

	public function isFeatureActive($feature) {
		$feature = $this->featureRepository->findOneByTitle($feature);
		foreach ($feature->getToggles() as $toggle) {
			if ($toggle->getGlobal() == TRUE) {
				return TRUE;
			}
		}

		return FALSE;
	}
}
?>