<?php

namespace Drupal\athena_xpdiffpermindeltas\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Xp diff per min deltas edit forms.
 *
 * @ingroup athena_xpdiffpermindeltas
 */
class XpDiffPerMinDeltasForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\athena_xpdiffpermindeltas\Entity\XpDiffPerMinDeltas */
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
        drupal_set_message($this->t('Created the %label Xp diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Xp diff per min deltas.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.xp_diff_per_min_deltas.canonical', ['xp_diff_per_min_deltas' => $entity->id()]);
  }

}
