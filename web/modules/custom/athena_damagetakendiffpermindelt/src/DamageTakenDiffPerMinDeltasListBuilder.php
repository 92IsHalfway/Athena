<?php

namespace Drupal\athena_damagetakendiffpermindelt;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Damage taken diff per min deltas entities.
 *
 * @ingroup athena_damagetakendiffpermindelt
 */
class DamageTakenDiffPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Damage taken diff per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.damage_taken_diff_per_min_deltas.edit_form',
      ['damage_taken_diff_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
