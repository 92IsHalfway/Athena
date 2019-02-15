<?php

/**
 * @file
 * Contains creeps_per_min_deltas.page.inc.
 *
 * Page callback for Creeps per min deltas entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Creeps per min deltas templates.
 *
 * Default template: creeps_per_min_deltas.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_creeps_per_min_deltas(array &$variables) {
  // Fetch CreepsPerMinDeltas Entity Object.
  $creeps_per_min_deltas = $variables['elements']['#creeps_per_min_deltas'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
