<?php

namespace Drupal\athena_xpdiffpermindeltas;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Xp diff per min deltas entity.
 *
 * @see \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltas.
 */
class XpDiffPerMinDeltasAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltasInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished xp diff per min deltas entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published xp diff per min deltas entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit xp diff per min deltas entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete xp diff per min deltas entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add xp diff per min deltas entities');
  }

}
