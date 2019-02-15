<?php

namespace Drupal\athena_csdiffpermindeltas\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Cs diff per min deltas entities.
 */
class CsDiffPerMinDeltasViewsData extends EntityViewsData {

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
