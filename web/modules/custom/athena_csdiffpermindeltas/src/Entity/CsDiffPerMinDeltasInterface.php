<?php

namespace Drupal\athena_csdiffpermindeltas\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Cs diff per min deltas entities.
 *
 * @ingroup athena_csdiffpermindeltas
 */
interface CsDiffPerMinDeltasInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Cs diff per min deltas name.
   *
   * @return string
   *   Name of the Cs diff per min deltas.
   */
  public function getName();

  /**
   * Sets the Cs diff per min deltas name.
   *
   * @param string $name
   *   The Cs diff per min deltas name.
   *
   * @return \Drupal\athena_csdiffpermindeltas\Entity\CsDiffPerMinDeltasInterface
   *   The called Cs diff per min deltas entity.
   */
  public function setName($name);

  /**
   * Gets the Cs diff per min deltas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Cs diff per min deltas.
   */
  public function getCreatedTime();

  /**
   * Sets the Cs diff per min deltas creation timestamp.
   *
   * @param int $timestamp
   *   The Cs diff per min deltas creation timestamp.
   *
   * @return \Drupal\athena_csdiffpermindeltas\Entity\CsDiffPerMinDeltasInterface
   *   The called Cs diff per min deltas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Cs diff per min deltas published status indicator.
   *
   * Unpublished Cs diff per min deltas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Cs diff per min deltas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Cs diff per min deltas.
   *
   * @param bool $published
   *   TRUE to set this Cs diff per min deltas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_csdiffpermindeltas\Entity\CsDiffPerMinDeltasInterface
   *   The called Cs diff per min deltas entity.
   */
  public function setPublished($published);

}
