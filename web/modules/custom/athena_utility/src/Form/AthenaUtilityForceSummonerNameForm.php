<?php

namespace Drupal\athena_utility\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\athena_utility\AthenaUtility;
use Drupal\athena_utility\AthenaLeagueUtility;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AthenaUtilityForceSummonerNameForm.
 *
 * @package Drupal\athena_utility\Form
 */
class AthenaUtilityForceSummonerNameForm extends FormBase {

  /**
   * An instance of the Athena Utility.
   *
   * @var \Drupal\athena_utility\AthenaUtility
   */
  protected $athenaUtility;

  /**
   * An instance of the Athena League Utility.
   *
   * @var \Drupal\athena_utility\AthenaLeagueUtility
   */
  protected $leagueUtility;

  /**
   * AthenaUtilityForceSummonerNameForm constructor.
   *
   * @param \Drupal\athena_utility\AthenaUtility $athenaUtility
   *   An instance of the Athena Utility.
   * @param \Drupal\athena_utility\AthenaLeagueUtility $leagueUtility
   *   An instance of the Athena League Utility.
   */
  public function __construct(AthenaUtility $athenaUtility, AthenaLeagueUtility $leagueUtility) {
    $this->athenaUtility = $athenaUtility;
    $this->leagueUtility = $leagueUtility;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('athena_utility.utility'),
      $container->get('athena_utility.league_utility')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'athena_utility_force_summoner_name_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $storage = $form_state->getStorage();

    if (isset($storage['summoner_name'])) {
      $form['fieldset'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Are You Sure?'),
      ];

      $form['fieldset']['summoner_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Summoner Name'),
        '#required' => TRUE,
        '#disabled' => TRUE,
        '#default_value' => $storage['summoner_name'],
      ];

      $form['fieldset']['submit'] = [
        '#type' => 'submit',
        '#value' => 'Confirm',
      ];

      $form['fieldset']['cancel'] = [
        '#type' => 'submit',
        '#value' => 'Cancel',
      ];
    }
    else {
      $form['fieldset'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Account Setup'),
      ];

      $form['fieldset']['summoner_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Summoner Name'),
        '#required' => TRUE,
      ];

      $form['fieldset']['submit'] = [
        '#type' => 'submit',
        '#value' => 'Submit',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $regex = '/^[0-9\p{L} _\.]+$/';
    $user_storage = $this->athenaUtility->getEntityStorage('user');

    if ($form_values['summoner_name'] == '') {
      $form_state->setErrorByName('summoner_name', 'Summoner Name is required.');
    }

    $comparison = preg_match($regex, $form_values['summoner_name']);

    if ($comparison !== 1) {
      $form_state->setErrorByName('summoner_name', 'Invalid Summoner Name.');
    }
    else {
      $summoner_details = $this->leagueUtility->getSummonerByName($form_values['summoner_name']);

      if (!isset($summoner_details['accountId'])) {
        $form_state->setErrorByName('summoner_name', 'That Summoner Name does not exist in the current region.');
      }
    }

    $query = $user_storage->getQuery();
    $query->condition('field_athena_league_summ_name', $form_values['summoner_name']);
    $uids = $query->execute();

    if (!empty($uids)) {
      $form_state->setErrorByName('summoner_name', 'That Summoner Name is already taken.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $trigger = $form_state->getTriggeringElement()['#value'];
    $to_store = [];
    $storage = $form_state->getStorage();

    if ($trigger == 'Submit') {
      $to_store['summoner_name'] = $form_values['summoner_name'];
      $form_state->setStorage($to_store);
      $form_state->setRebuild();
    }
    elseif ($trigger == 'Confirm') {
      $summoner_name = $storage['summoner_name'];
      $summoner_details = $this->leagueUtility->getSummonerByName($summoner_name);

      $user = $this->athenaUtility->loadUser($this->athenaUtility->getCurrentUser()->id());

      $this->leagueUtility->updateSummonerDetails($user->id(), $summoner_details);

      $form_state->setRedirect('<front>');
    }
    else {
      $form_state->setRedirect('athena_utility.force_summoner_name');
    }
  }

}
