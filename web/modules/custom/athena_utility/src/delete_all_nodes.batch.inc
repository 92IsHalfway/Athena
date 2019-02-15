<?php

/**
 * @file
 * Batch function for deleting a node.
 */

use Drupal\Core\Entity\EntityStorageException;

/**
 * Deletes a node.
 *
 * @param string|int $nid
 *   The nid of the node to delete.
 */
function batch_delete_node($nid) {
  /* @var $athenaUtility \Drupal\athena_utility\AthenaUtility */ $athenaUtility = \Drupal::service('athena_utility.utility');

  $node = $athenaUtility->loadNode($nid);

  if ($node != NULL) {
    try {
      $node->delete();
    }
    catch (EntityStorageException $e) {
      $athenaUtility->setLogMessage('athena_utility', 'Entity Storage Exception 13.', 'error');
    }
  }
}
