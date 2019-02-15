<?php

namespace Drupal\athena_damagetakenpermindeltas;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Damage taken per min deltas entity.
 *
 * @see \Drupal\athena_damagetakenpermindeltas\Entity\DamageTakenPerMinDeltas.
 */
class DamageTakenPerMinDeltasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_damagetakenpermindeltas\Entity\DamageTakenPerMinDeltasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished damage taken per min deltas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published damage taken per min deltas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit damage taken per min deltas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete damage taken per min deltas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add damage taken per min deltas entities');
  }

}
