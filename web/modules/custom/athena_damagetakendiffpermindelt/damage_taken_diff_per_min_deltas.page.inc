<?php

/**
 * @file
 * Contains damage_taken_diff_per_min_deltas.page.inc.
 *
 * Page callback for Damage taken diff per min deltas entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Damage taken diff per min deltas templates.
 *
 * Default template: damage_taken_diff_per_min_deltas.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_damage_taken_diff_per_min_deltas(array &$variables) {
  // Fetch DamageTakenDiffPerMinDeltas Entity Object.
  $damage_taken_diff_per_min_deltas = $variables['elements']['#damage_taken_diff_per_min_deltas'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
