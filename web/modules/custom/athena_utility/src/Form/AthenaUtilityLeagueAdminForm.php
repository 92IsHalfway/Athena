<?php

namespace Drupal\athena_utility\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AthenaUtilityLeagueAdminForm.
 *
 * @package Drupal\athena_utility\Form
 */
class AthenaUtilityLeagueAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'athena_utility.league_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'athena_utility_league_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('athena_utility.league_settings');
    $region = $config->get('region');
    $api_key = $config->get('api_key');

    $form['region'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Region'),
      '#required' => TRUE,
      '#default_value' => $region,
    ];

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#required' => TRUE,
      '#default_value' => $api_key,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();

    if ($form_values['region'] == '') {
      $form_state->setErrorByName('region', 'Region is required.');
    }

    if ($form_values['api_key'] == '') {
      $form_state->setErrorByName('api_key', 'API Key is required.');
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $editable_config = $this->configFactory->getEditable('athena_utility.league_settings');

    $editable_config->set('region', $form_state->getValue('region'));
    $editable_config->set('api_key', $form_state->getValue('api_key'));

    $editable_config->save();

    parent::submitForm($form, $form_state);
  }

}
