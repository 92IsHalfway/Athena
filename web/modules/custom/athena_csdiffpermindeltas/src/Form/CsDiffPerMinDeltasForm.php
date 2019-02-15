<?php

namespace Drupal\athena_csdiffpermindeltas\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Cs diff per min deltas edit forms.
 *
 * @ingroup athena_csdiffpermindeltas
 */
class CsDiffPerMinDeltasForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\athena_csdiffpermindeltas\Entity\CsDiffPerMinDeltas */
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
        drupal_set_message($this->t('Created the %label Cs diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Cs diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.cs_diff_per_min_deltas.canonical', ['cs_diff_per_min_deltas' => $entity->id()]);
  }

}
