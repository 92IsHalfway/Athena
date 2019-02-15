<?php

namespace Drupal\athena_damagetakendiffpermindelt\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Damage taken diff per min deltas entities.
 */
class DamageTakenDiffPerMinDeltasViewsData extends EntityViewsData {

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
