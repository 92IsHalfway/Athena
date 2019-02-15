<?php

namespace Drupal\athena_damagetakenpermindeltas\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Damage taken per min deltas entity.
 *
 * @ingroup athena_damagetakenpermindeltas
 *
 * @ContentEntityType(
 *   id = "damage_taken_per_min_deltas",
 *   label = @Translation("Damage taken per min deltas"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\athena_damagetakenpermindeltas\DamageTakenPerMinDeltasListBuilder",
 *     "views_data" = "Drupal\athena_damagetakenpermindeltas\Entity\DamageTakenPerMinDeltasViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\athena_damagetakenpermindeltas\Form\DamageTakenPerMinDeltasForm",
 *       "add" = "Drupal\athena_damagetakenpermindeltas\Form\DamageTakenPerMinDeltasForm",
 *       "edit" = "Drupal\athena_damagetakenpermindeltas\Form\DamageTakenPerMinDeltasForm",
 *       "delete" = "Drupal\athena_damagetakenpermindeltas\Form\DamageTakenPerMinDeltasDeleteForm",
 *     },
 *     "access" = "Drupal\athena_damagetakenpermindeltas\DamageTakenPerMinDeltasAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\athena_damagetakenpermindeltas\DamageTakenPerMinDeltasHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "damage_taken_per_min_deltas",
 *   admin_permission = "administer damage taken per min deltas entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/damage_taken_per_min_deltas/{damage_taken_per_min_deltas}",
 *     "add-form" = "/admin/structure/damage_taken_per_min_deltas/add",
 *     "edit-form" = "/admin/structure/damage_taken_per_min_deltas/{damage_taken_per_min_deltas}/edit",
 *     "delete-form" = "/admin/structure/damage_taken_per_min_deltas/{damage_taken_per_min_deltas}/delete",
 *     "collection" = "/admin/structure/damage_taken_per_min_deltas",
 *   },
 *   field_ui_base_route = "damage_taken_per_min_deltas.settings"
 * )
 */
class DamageTakenPerMinDeltas extends ContentEntityBase implements DamageTakenPerMinDeltasInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Damage taken per min deltas entity.'))
      ->setRevisionable(FALSE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Damage taken per min deltas entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Damage taken per min deltas is published.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['game_id'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Game ID'))
      ->setDescription(t('The ID of the game.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 7,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 7,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['account_id'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Account ID'))
      ->setDescription(t('The ID of the account.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 8,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 8,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    return $fields;
  }

}
