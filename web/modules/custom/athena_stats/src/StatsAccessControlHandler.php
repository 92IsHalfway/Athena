<?php

namespace Drupal\athena_stats;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Stats entity.
 *
 * @see \Drupal\athena_stats\Entity\Stats.
 */
class StatsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\athena_stats\Entity\StatsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished stats entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published stats entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit stats entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete stats entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add stats entities');
  }

}
