<?php

namespace Drupal\athena_damagetakendiffpermindelt;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Damage taken diff per min deltas entity.
 *
 * @see \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltas.
 */
class DamageTakenDiffPerMinDeltasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished damage taken diff per min deltas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published damage taken diff per min deltas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit damage taken diff per min deltas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete damage taken diff per min deltas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add damage taken diff per min deltas entities');
  }

}
