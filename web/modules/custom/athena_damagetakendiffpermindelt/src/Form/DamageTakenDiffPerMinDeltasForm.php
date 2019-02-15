<?php

namespace Drupal\athena_damagetakendiffpermindelt\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Damage taken diff per min deltas edit forms.
 *
 * @ingroup athena_damagetakendiffpermindelt
 */
class DamageTakenDiffPerMinDeltasForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\athena_damagetakendiffpermindelt\Entity\DamageTakenDiffPerMinDeltas */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Damage taken diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Damage taken diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.damage_taken_diff_per_min_deltas.canonical', ['damage_taken_diff_per_min_deltas' => $entity->id()]);
  }

}
