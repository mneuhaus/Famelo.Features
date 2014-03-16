<?php
namespace Famelo\Features\Core;

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
class ConditionMatcher {

	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var string
	 */
	protected $feature;

	public function __construct($feature) {
		$this->feature = $feature;
	}

	/**
	 * Active for everyone
	 *
	 * @return boolean
	 * @api
	 */
	public function everyone() {
		return TRUE;
	}

	/**
	 * Check if the current user has a specific role
	 *
	 * @param string $role
	 * @return boolean
	 * @api
	 */
	public function hasRole($role) {
		return $this->securityContext->hasRole($role);
	}

	/**
	 * Check if the current user equals the specified username
	 *
	 * @param string $username
	 * @return boolean
	 * @api
	 */
	public function isUser($username) {
		if ($this->isGuest() === TRUE) {
			return FALSE;
		}
		return $this->securityContext->getAccount()->getAccountIdentifier() == $username;
	}

	/**
	 * Check if the current user is a guest
	 *
	 * @return boolean
	 * @api
	 */
	public function isGuest() {
		return !$this->isNotGuest();
	}

	/**
	 * Check if the current user is not a guest
	 *
	 * @return boolean
	 * @api
	 */
	public function isNotGuest() {
		$account = $this->securityContext->getAccount();
		return $account instanceof Account;
	}

	/**
	 * Check if the current date is after the specified date
	 *
	 * @param string $date
	 * @return boolean
	 * @api
	 */
	public function afterDate($date) {
		$release = new \DateTime($date);
		$now = new \DateTime();
		return $now > $release;
	}

	/**
	 * Check if the current user falls under the percentage
	 *
	 * @param integer $percentage
	 * @return boolean
	 * @api
	 */
	public function userPercentage($percentage) {
		$username = $this->securityContext->getAccount()->getAccountIdentifier();
		$chance = hexdec(substr(sha1($username . $this->feature), 0, 4)) / pow(2,16);
		if ($percentage > 1) {
			$percentage = $percentage / 100;
		}
		return $chance > $percentage;
	}

	/**
	 * Check if the current users' ip address matches
	 *
	 * @param string $ip
	 * @return boolean
	 * @api
	 */
	public function clientIp($ip) {
		$clientIp = NULL;
		if (getenv('HTTP_CLIENT_IP')){
			$clientIp = getenv('HTTP_CLIENT_IP');
		} else if(getenv('HTTP_X_FORWARDED_FOR')) {
			$clientIp = getenv('HTTP_X_FORWARDED_FOR');
		} else if(getenv('HTTP_X_FORWARDED')) {
			$clientIp = getenv('HTTP_X_FORWARDED');
		} else if(getenv('HTTP_FORWARDED_FOR')) {
			$clientIp = getenv('HTTP_FORWARDED_FOR');
		} else if(getenv('HTTP_FORWARDED')) {
			$clientIp = getenv('HTTP_FORWARDED');
		} else if(getenv('REMOTE_ADDR')){
			$clientIp = getenv('REMOTE_ADDR');
		}
		return $clientIp == $ip;
	}
}
