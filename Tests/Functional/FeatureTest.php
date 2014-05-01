<?php
namespace Famelo\Features\Tests\Functional;
/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Core\ApplicationContext;
use TYPO3\Flow\Http\Request as HttpRequest;
use TYPO3\Flow\Http\Uri;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Policy\Role;

/**
 *
 */
class FeatureTest extends \TYPO3\Flow\Tests\FunctionalTestCase {
	/**
	 * @var \Famelo\Features\FeatureService
	 */
	protected $featureService;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	*/
	public function setUp() {
		parent::setUp();
		$this->featureService = $this->getMock('Famelo\Features\FeatureService', array('createConditionMatcher'));

		$this->configurationManager = $this->getAccessibleMock('TYPO3\Flow\Configuration\ConfigurationManager', array('getConfiguration'), array(new ApplicationContext('Testing')), '', FALSE);
		$this->inject($this->featureService, 'configurationManager', $this->configurationManager);
		$this->configurationManager
				->expects($this->at(0))
				->method('getConfiguration')
				->with('Settings', 'Famelo.Features')
				->will($this->returnValue(array(
					'conditionMatcher' => '\Famelo\Features\Core\ConditionMatcher',
    				'noMatchBehavior' => 'exception'
				)));

		$eelEvaluator = $this->objectManager->get('TYPO3\Eel\CompilingEvaluator');
		$this->inject($this->featureService, 'eelEvaluator', $eelEvaluator);

		$conditionMatcher = new \Famelo\Features\Core\ConditionMatcher('foo');
		$this->featureService
				->expects($this->any())
				->method('createConditionMatcher')
				->will($this->returnValue($conditionMatcher));

		$this->securityContext = $this->getMock('TYPO3\Flow\Security\Context', array(
			'getAccount',
			'hasRole'
		));
		$this->inject($conditionMatcher, 'securityContext', $this->securityContext);

	}

	/**
	 * @test
	 */
	public function isUserConditionReturnsTrueForMatchingUser() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'isUser("toni")'
  					)
				)));
		$account = new Account();
		$account->setAccountIdentifier('toni');
		$this->securityContext
				->expects($this->any())
				->method('getAccount')
				->will($this->returnValue($account));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function isUserConditionReturnsFalseForNotMatchingUser() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'isUser("toni")'
  					)
				)));
		$account = new Account();
		$account->setAccountIdentifier('max');
		$this->securityContext
				->expects($this->any())
				->method('getAccount')
				->will($this->returnValue($account));

		$this->assertFalse($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function hasRoleConditionReturnsTrueForMatchingRole() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'hasRole("editor")'
  					)
				)));
		$this->securityContext
				->expects($this->any())
				->method('hasRole')
				->with('editor')
				->will($this->returnValue(TRUE));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function hasRoleConditionReturnsFalseForNotMatchingRole() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'hasRole("administrator")'
  					)
				)));
		$this->securityContext
				->expects($this->any())
				->method('hasRole')
				->with('administrator')
				->will($this->returnValue(FALSE));

		$this->assertFalse($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function afterDateCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'afterDate("22.11.2013")'
  					)
				)));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function isGuestCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'isGuest()'
  					)
				)));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function isNotGuestCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'isNotGuest()'
  					)
				)));
		$account = new Account();
		$account->setAccountIdentifier('toni');
		$this->securityContext
				->expects($this->any())
				->method('getAccount')
				->will($this->returnValue($account));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function userPercentageCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'userPercentage(25)'
  					)
				)));
		$account = new Account();
		$account->setAccountIdentifier('toni');
		$this->securityContext
				->expects($this->any())
				->method('getAccount')
				->will($this->returnValue($account));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function clientIpCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'clientIp("")'
  					)
				)));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}

	/**
	 * @test
	 */
	public function customMatcherCondition() {
		$this->configurationManager
				->expects($this->at(1))
				->method('getConfiguration')
				->with('Features')
				->will($this->returnValue(array(
					array(
  						'name' => 'foo',
  						'condition' => 'test.someCondition("foo")'
  					)
				)));

		$this->assertTrue($this->featureService->isFeatureActive('foo'));
	}
}
