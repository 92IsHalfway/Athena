<?php

/**
 * @file
 * Contains xp_diff_per_min_deltas.page.inc.
 *
 * Page callback for Xp diff per min deltas entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Xp diff per min deltas templates.
 *
 * Default template: xp_diff_per_min_deltas.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_xp_diff_per_min_deltas(array &$variables) {
  // Fetch XpDiffPerMinDeltas Entity Object.
  $xp_diff_per_min_deltas = $variables['elements']['#xp_diff_per_min_deltas'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
