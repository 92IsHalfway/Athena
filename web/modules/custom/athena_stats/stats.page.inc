<?php

/**
 * @file
 * Contains stats.page.inc.
 *
 * Page callback for Stats entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Stats templates.
 *
 * Default template: stats.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_stats(array &$variables) {
  // Fetch Stats Entity Object.
  $stats = $variables['elements']['#stats'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
