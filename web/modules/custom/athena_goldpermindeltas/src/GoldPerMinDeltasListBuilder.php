<?php

namespace Drupal\athena_goldpermindeltas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Gold per min deltas entities.
 *
 * @ingroup athena_goldpermindeltas
 */
class GoldPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Gold per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.gold_per_min_deltas.edit_form',
      ['gold_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
