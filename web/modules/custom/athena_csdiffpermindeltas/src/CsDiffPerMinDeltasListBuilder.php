<?php

namespace Drupal\athena_csdiffpermindeltas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Cs diff per min deltas entities.
 *
 * @ingroup athena_csdiffpermindeltas
 */
class CsDiffPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Cs diff per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_csdiffpermindeltas\Entity\CsDiffPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.cs_diff_per_min_deltas.edit_form',
      ['cs_diff_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
