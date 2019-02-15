<?php

namespace Drupal\athena_stats\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Stats entities.
 *
 * @ingroup athena_stats
 */
interface StatsInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Stats name.
   *
   * @return string
   *   Name of the Stats.
   */
  public function getName();

  /**
   * Sets the Stats name.
   *
   * @param string $name
   *   The Stats name.
   *
   * @return \Drupal\athena_stats\Entity\StatsInterface
   *   The called Stats entity.
   */
  public function setName($name);

  /**
   * Gets the Stats creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Stats.
   */
  public function getCreatedTime();

  /**
   * Sets the Stats creation timestamp.
   *
   * @param int $timestamp
   *   The Stats creation timestamp.
   *
   * @return \Drupal\athena_stats\Entity\StatsInterface
   *   The called Stats entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Stats published status indicator.
   *
   * Unpublished Stats are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Stats is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Stats.
   *
   * @param bool $published
   *   TRUE to set this Stats to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_stats\Entity\StatsInterface
   *   The called Stats entity.
   */
  public function setPublished($published);

}
