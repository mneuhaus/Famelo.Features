<?php
namespace Famelo\Features\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @see TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode::convertArgumentValue()
 * @api
 */
class ActiveViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractConditionViewHelper {
	/**
	 * The featureService
	 *
	 * @var \Famelo\Features\FeatureService
	 * @Flow\Inject
	 */
	protected $featureService;

	/**
	 * renders <f:then> child if $condition is true, otherwise renders <f:else> child.
	 *
	 * @param string $feature
	 * @return string the rendered string
	 * @api
	 */
	public function render($feature) {
		if ($this->featureService->isFeatureActive($feature)) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}
?>
