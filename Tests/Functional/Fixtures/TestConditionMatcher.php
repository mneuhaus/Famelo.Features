<?php
namespace Famelo\Features\Tests\Functional\Fixtures;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;

/**
 *
 */
class TestConditionMatcher implements \Famelo\Features\Core\ConditionMatcherInterface{
	/**
      * contains short name for this matcher used for reference in the eel expression
      */
	const NAME = 'test';

	/**
	 * @var string
	 */
	protected $feature;

	public function __construct($feature) {
		$this->feature = $feature;
	}

	/**
	 * @return boolean
	 */
	public function someCondition() {
		return TRUE;
	}

}
