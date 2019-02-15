<?php

namespace Drupal\athena_damagetakenpermindeltas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Damage taken per min deltas entities.
 *
 * @ingroup athena_damagetakenpermindeltas
 */
class DamageTakenPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Damage taken per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_damagetakenpermindeltas\Entity\DamageTakenPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.damage_taken_per_min_deltas.edit_form',
      ['damage_taken_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
