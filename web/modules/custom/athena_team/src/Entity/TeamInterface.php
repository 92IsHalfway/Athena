<?php

namespace Drupal\athena_team\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Team entities.
 *
 * @ingroup athena_team
 */
interface TeamInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Team name.
   *
   * @return string
   *   Name of the Team.
   */
  public function getName();

  /**
   * Sets the Team name.
   *
   * @param string $name
   *   The Team name.
   *
   * @return \Drupal\athena_team\Entity\TeamInterface
   *   The called Team entity.
   */
  public function setName($name);

  /**
   * Gets the Team creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Team.
   */
  public function getCreatedTime();

  /**
   * Sets the Team creation timestamp.
   *
   * @param int $timestamp
   *   The Team creation timestamp.
   *
   * @return \Drupal\athena_team\Entity\TeamInterface
   *   The called Team entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Team published status indicator.
   *
   * Unpublished Team are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Team is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Team.
   *
   * @param bool $published
   *   TRUE to set this Team to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\athena_team\Entity\TeamInterface
   *   The called Team entity.
   */
  public function setPublished($published);

}
