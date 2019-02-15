<?php

namespace Drupal\athena_goldpermindeltas;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Gold per min deltas entity.
 *
 * @see \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltas.
 */
class GoldPerMinDeltasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_goldpermindeltas\Entity\GoldPerMinDeltasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished gold per min deltas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published gold per min deltas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit gold per min deltas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete gold per min deltas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add gold per min deltas entities');
  }

}
