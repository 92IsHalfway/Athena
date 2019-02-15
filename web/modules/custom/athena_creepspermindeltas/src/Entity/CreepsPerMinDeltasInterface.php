<?php

namespace Drupal\athena_creepspermindeltas\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Creeps per min deltas entities.
 *
 * @ingroup athena_creepspermindeltas
 */
interface CreepsPerMinDeltasInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Creeps per min deltas name.
   *
   * @return string
   *   Name of the Creeps per min deltas.
   */
  public function getName();

  /**
   * Sets the Creeps per min deltas name.
   *
   * @param string $name
   *   The Creeps per min deltas name.
   *
   * @return \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltasInterface
   *   The called Creeps per min deltas entity.
   */
  public function setName($name);

  /**
   * Gets the Creeps per min deltas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Creeps per min deltas.
   */
  public function getCreatedTime();

  /**
   * Sets the Creeps per min deltas creation timestamp.
   *
   * @param int $timestamp
   *   The Creeps per min deltas creation timestamp.
   *
   * @return \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltasInterface
   *   The called Creeps per min deltas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Creeps per min deltas published status indicator.
   *
   * Unpublished Creeps per min deltas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Creeps per min deltas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Creeps per min deltas.
   *
   * @param bool $published
   *   TRUE to set this Creeps per min deltas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltasInterface
   *   The called Creeps per min deltas entity.
   */
  public function setPublished($published);

}
