<?php

namespace Drupal\athena_damagetakendiffpermindelt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DamageTakenDiffPerMinDeltasSettingsForm.
 *
 * @ingroup athena_damagetakendiffpermindelt
 */
class DamageTakenDiffPerMinDeltasSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'damagetakendiffpermindeltas_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Damage taken diff per min deltas entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['damagetakendiffpermindeltas_settings']['#markup'] = 'Settings form for Damage taken diff per min deltas entities. Manage field settings here.';
    return $form;
  }

}
