<?php

namespace Drupal\athena_damagetakenpermindeltas\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Damage taken per min deltas entities.
 */
class DamageTakenPerMinDeltasViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
