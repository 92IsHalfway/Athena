<?php

namespace Drupal\athena_xpdiffpermindeltas\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Xp diff per min deltas entities.
 *
 * @ingroup athena_xpdiffpermindeltas
 */
interface XpDiffPerMinDeltasInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Xp diff per min deltas name.
   *
   * @return string
   *   Name of the Xp diff per min deltas.
   */
  public function getName();

  /**
   * Sets the Xp diff per min deltas name.
   *
   * @param string $name
   *   The Xp diff per min deltas name.
   *
   * @return \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltasInterface
   *   The called Xp diff per min deltas entity.
   */
  public function setName($name);

  /**
   * Gets the Xp diff per min deltas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Xp diff per min deltas.
   */
  public function getCreatedTime();

  /**
   * Sets the Xp diff per min deltas creation timestamp.
   *
   * @param int $timestamp
   *   The Xp diff per min deltas creation timestamp.
   *
   * @return \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltasInterface
   *   The called Xp diff per min deltas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Xp diff per min deltas published status indicator.
   *
   * Unpublished Xp diff per min deltas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Xp diff per min deltas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Xp diff per min deltas.
   *
   * @param bool $published
   *   TRUE to set this Xp diff per min deltas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltasInterface
   *   The called Xp diff per min deltas entity.
   */
  public function setPublished($published);

}
