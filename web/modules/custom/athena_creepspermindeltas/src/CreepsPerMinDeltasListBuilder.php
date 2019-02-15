<?php

namespace Drupal\athena_creepspermindeltas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Creeps per min deltas entities.
 *
 * @ingroup athena_creepspermindeltas
 */
class CreepsPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Creeps per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.creeps_per_min_deltas.edit_form',
      ['creeps_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
