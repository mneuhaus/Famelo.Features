<?php
namespace Famelo\Features\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Switch
 *
 * @Flow\Entity
 */
class Toggle {

	/**
	 * The created
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * The start
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $start = NULL;

	/**
	 * The stop
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $stop = NULL;

	/**
	 * The role
	 * @var string
	 */
	protected $role = '';

	/**
	 * The account
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne(inversedBy="features", cascade={"all"})
	 */
	protected $account;

	/**
	 * The global
	 * @var boolean
	 */
	protected $global = FALSE;

	public function __construct() {
		$this->created = new \DateTime();
	}

	public function __toString() {
		if ($this->global !== FALSE) {
			return 'Globally activated';
		}
		if ($this->getAccount() !== NULL) {
			return strval($this->getAccount());
		}
		if ($this->getRole() !== NULL) {
			return strval($this->getRole());
		}
		return 'not active';
	}

	/**
	 * Get the Switch's created
	 *
	 * @return \DateTime The Switch's created
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Sets this Switch's created
	 *
	 * @param \DateTime $created The Switch's created
	 * @return void
	 */
	public function setCreated($created) {
		$this->created = $created;
	}

	/**
	 * Get the Switch's start
	 *
	 * @return \DateTime The Switch's start
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * Sets this Switch's start
	 *
	 * @param \DateTime $start The Switch's start
	 * @return void
	 */
	public function setStart($start) {
		$this->start = $start;
	}

	/**
	 * Get the Switch's stop
	 *
	 * @return \DateTime The Switch's stop
	 */
	public function getStop() {
		return $this->stop;
	}

	/**
	 * Sets this Switch's stop
	 *
	 * @param \DateTime $stop The Switch's stop
	 * @return void
	 */
	public function setStop($stop) {
		$this->stop = $stop;
	}

	/**
	 * Get the Switch's role
	 *
	 * @return string The Switch's role
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * Sets this Switch's role
	 *
	 * @param string $role The Switch's role
	 * @return void
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * Get the Switch's account
	 *
	 * @return \TYPO3\Flow\Security\Account The Switch's account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * Sets this Switch's account
	 *
	 * @param \TYPO3\Flow\Security\Account $account The Switch's account
	 * @return void
	 */
	public function setAccount($account) {
		$this->account = $account;
	}

	/**
	 * Get the Switch's global
	 *
	 * @return boolean The Switch's global
	 */
	public function getGlobal() {
		return $this->global;
	}

	/**
	 * Sets this Switch's global
	 *
	 * @param boolean $global The Switch's global
	 * @return void
	 */
	public function setGlobal($global) {
		$this->global = $global;
	}

}
?>