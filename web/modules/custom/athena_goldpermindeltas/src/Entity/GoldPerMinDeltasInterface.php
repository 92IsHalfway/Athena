<?php

namespace Drupal\athena_goldpermindeltas\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Gold per min deltas entities.
 *
 * @ingroup athena_goldpermindeltas
 */
interface GoldPerMinDeltasInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Gold per min deltas name.
   *
   * @return string
   *   Name of the Gold per min deltas.
   */
  public function getName();

  /**
   * Sets the Gold per min deltas name.
   *
   * @param string $name
   *   The Gold per min deltas name.
   *
   * @return \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltasInterface
   *   The called Gold per min deltas entity.
   */
  public function setName($name);

  /**
   * Gets the Gold per min deltas creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Gold per min deltas.
   */
  public function getCreatedTime();

  /**
   * Sets the Gold per min deltas creation timestamp.
   *
   * @param int $timestamp
   *   The Gold per min deltas creation timestamp.
   *
   * @return \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltasInterface
   *   The called Gold per min deltas entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Gold per min deltas published status indicator.
   *
   * Unpublished Gold per min deltas are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Gold per min deltas is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Gold per min deltas.
   *
   * @param bool $published
   *   TRUE to set this Gold per min deltas to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltasInterface
   *   The called Gold per min deltas entity.
   */
  public function setPublished($published);

}
