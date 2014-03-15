<?php
namespace Famelo\Features;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Eel\Context;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Exception;

/**
 * @Flow\Scope("singleton")
 */
class FeatureService {
	
	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Eel\CompilingEvaluator
	 */
	protected $eelEvaluator;

	/**
	 * @var array
	 */
	protected $runtimeCache = array();

	/**
	 * @param $requestedFeature
	 * @return mixed
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function isFeatureActive($requestedFeature) {
		if (!isset($this->runtimeCache[$requestedFeature])) {
			$this->runtimeCache[$requestedFeature] = NULL;

			$settings = $this->configurationManager->getConfiguration('Settings', 'Famelo.Features');

			$features = $this->configurationManager->getConfiguration('Features');
			$conditionMatcher = new $settings['conditionMatcher']($requestedFeature);
			$context = new Context($conditionMatcher);

			foreach ($features as $feature) {
				if ($feature['name'] == $requestedFeature && isset($feature['condition'])) {
					$this->runtimeCache[$requestedFeature] =  $this->eelEvaluator->evaluate($feature['condition'], $context);
				}
			}

			if ($this->runtimeCache[$requestedFeature] === NULL) {
				switch ($settings['noMatchBehavior']) {
					case 'active':
						$this->runtimeCache[$requestedFeature] = TRUE;
						break;

					case 'inactive':
						$this->runtimeCache[$requestedFeature] = FALSE;
						break;

					case 'exception':
					default:
						throw new Exception('The Feature you\'re trying to use does not exist: ' . $requestedFeature);
						break;
				}
			}
		}

		return $this->runtimeCache[$requestedFeature];
	}
}
