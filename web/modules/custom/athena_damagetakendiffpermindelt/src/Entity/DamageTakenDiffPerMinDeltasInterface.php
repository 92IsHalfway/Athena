<?php

namespace Drupal\athena_damagetakendiffpermindelt\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Damage taken diff per min deltas entities.
 *
 * @ingroup athena_damagetakendiffpermindelt
 */
interface DamageTakenDiffPerMinDeltasInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Damage taken diff per min deltas name.
   *
   * @return string
   *   Name of the Damage taken diff per min deltas.
   */
  public function getName();

  /**
   * Sets the Damage taken diff per min deltas name.
   *
   * @param string $name
   *   The Damage taken diff per min deltas name.
   *
   * @return \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltasInterface
   *   The called Damage taken diff per min deltas entity.
   */
  public function setName($name);

  /**
   * Gets the Damage taken diff per min deltas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Damage taken diff per min deltas.
   */
  public function getCreatedTime();

  /**
   * Sets the Damage taken diff per min deltas creation timestamp.
   *
   * @param int $timestamp
   *   The Damage taken diff per min deltas creation timestamp.
   *
   * @return \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltasInterface
   *   The called Damage taken diff per min deltas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Damage taken diff per min deltas published status indicator.
   *
   * Unpublished Damage taken diff per min deltas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Damage taken diff per min deltas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Damage taken diff per min deltas.
   *
   * @param bool $published
   *   TRUE to set this Damage taken diff per min deltas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltasInterface
   *   The called Damage taken diff per min deltas entity.
   */
  public function setPublished($published);

}
