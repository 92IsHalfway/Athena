<?php

namespace Drupal\athena_creepspermindeltas;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Creeps per min deltas entity.
 *
 * @see \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltas.
 */
class CreepsPerMinDeltasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_creepspermindeltas\Entity\CreepsPerMinDeltasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished creeps per min deltas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published creeps per min deltas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit creeps per min deltas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete creeps per min deltas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add creeps per min deltas entities');
  }

}
