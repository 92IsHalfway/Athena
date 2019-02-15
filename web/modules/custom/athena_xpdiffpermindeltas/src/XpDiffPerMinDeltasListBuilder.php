<?php

namespace Drupal\athena_xpdiffpermindeltas;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Xp diff per min deltas entities.
 *
 * @ingroup athena_xpdiffpermindeltas
 */
class XpDiffPerMinDeltasListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Xp diff per min deltas ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltas */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.xp_diff_per_min_deltas.edit_form',
      ['xp_diff_per_min_deltas' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
