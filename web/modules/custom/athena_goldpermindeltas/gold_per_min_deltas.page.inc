<?php

/**
 * @file
 * Contains gold_per_min_deltas.page.inc.
 *
 * Page callback for Gold per min deltas entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Gold per min deltas templates.
 *
 * Default template: gold_per_min_deltas.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_gold_per_min_deltas(array &$variables) {
  // Fetch GoldPerMinDeltas Entity Object.
  $gold_per_min_deltas = $variables['elements']['#gold_per_min_deltas'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
