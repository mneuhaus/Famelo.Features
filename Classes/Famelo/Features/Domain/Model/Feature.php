<?php
namespace Famelo\Features\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.Features".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Feature
 *
 * @Flow\Entity
 */
class Feature {

	/**
	 * The title
	 * @var string
	 */
	protected $title;

	/**
	 * The created
	 * @var \DateTime
	 */
	protected $created = NULL;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\Features\Domain\Model\Toggle>
	 * @ORM\ManyToMany(cascade={"all"})
	 */
	protected $toggles;

	public function __construct() {
		$this->created = new \DateTime();
	}

	public function __toString() {
		return $this->getTitle();
	}

	/**
	 * Get the Feature's title
	 *
	 * @return string The Feature's title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets this Feature's title
	 *
	 * @param string $title The Feature's title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the Feature's created
	 *
	 * @return \DateTime The Feature's created
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Sets this Feature's created
	 *
	 * @param \DateTime $created The Feature's created
	 * @return void
	 */
	public function setCreated($created) {
		$this->created = $created;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\Features\Domain\Model\Toggle> $toggles
	 */
	public function setToggles($toggles) {
		$this->toggles = $toggles;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\Features\Domain\Model\Toggle>
	 */
	public function getToggles() {
		return $this->toggles;
	}
}
?>