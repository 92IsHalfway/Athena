<?php

namespace Drupal\athena_utility;

use Drupal\Core\Entity\EntityStorageException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;

/**
 * Class AthenaUtility.
 *
 * @package Drupal\athena_utility
 */
class AthenaUtility {

  /**
   * Drupal core EntityTypeManager, replaces QueryFactory.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal core AccountProxy.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Drupal core ConfigFactory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal core Messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Drupal core EntityFieldManager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Drupal core DateFormatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Drupal core UrlGenerator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * Drupal core LinkGenerator.
   *
   * @var \Drupal\Core\Utility\LinkGeneratorInterface
   */
  protected $linkGenerator;

  /**
   * Drupal core CacheBackend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Drupal core LoggerChannelFactory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * AthenaUtility constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   An instance of the core service EntityTypeManagerInterface.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   An instance of the core service AccountProxyInterface.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   An instance of the core service ConfigFactoryInterface.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   An instance of the core service MessengerInterface.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   *   An instance of the core service EntityFieldManagerInterface.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   An instance of the core service DateFormatterInterface.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $urlGenerator
   *   An instance of the core service UrlGeneratorInterface.
   * @param \Drupal\Core\Utility\LinkGeneratorInterface $linkGenerator
   *   An instance of the core service LinkGeneratorInterface.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   An instance of the core service CacheBackendInterface.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerFactory
   *   An instance of the core service LoggerChannelFactoryInterface.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, AccountProxyInterface $currentUser, ConfigFactoryInterface $configFactory, MessengerInterface $messenger, EntityFieldManagerInterface $entityFieldManager, DateFormatterInterface $dateFormatter, UrlGeneratorInterface $urlGenerator, LinkGeneratorInterface $linkGenerator, CacheBackendInterface $cache, LoggerChannelFactoryInterface $loggerFactory) {
    $this->entityTypeManager = $entityTypeManager;
    $this->currentUser = $currentUser;
    $this->configFactory = $configFactory;
    $this->messenger = $messenger;
    $this->entityFieldManager = $entityFieldManager;
    $this->dateFormatter = $dateFormatter;
    $this->urlGenerator = $urlGenerator;
    $this->linkGenerator = $linkGenerator;
    $this->cache = $cache;
    $this->loggerFactory = $loggerFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('config.factory'),
      $container->get('messenger'),
      $container->get('entity_field.manager'),
      $container->get('date.formatter'),
      $container->get('url_generator'),
      $container->get('link_generator'),
      $container->get('cache.default'),
      $container->get('logger.factory')
    );
  }

  /**
   * Sets a message - drupal_set_message is deprecated.
   *
   * @param string $message
   *   The message to be displayed.
   * @param string $type
   *   The type of message to be displayed (defaults to status).
   *
   * @return \Drupal\Core\Messenger\MessengerInterface
   *   Returns $this.
   */
  public function setMessage($message, $type = 'status') {
    return $this->messenger->addMessage($message, $type);
  }

  /**
   * Returns the storage for a given entity type.
   *
   * @param string $type
   *   The type of storage to return.
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface|null
   *   The entity storage.
   */
  public function getEntityStorage($type) {
    $storage = NULL;

    try {
      $storage = $this->entityTypeManager->getStorage($type);
    }
    catch (InvalidPluginDefinitionException $e) {
      $this->setMessage('Invalid Plugin Definition Exception.', 'error');
    }
    catch (PluginNotFoundException $e) {
      $this->setMessage('Plugin Not Found Exception.', 'error');
    }

    return $storage;
  }

  /**
   * Accepts a Node ID and returns the node loaded from storage.
   *
   * @param int|string $nid
   *   The id of the node.
   *
   * @return \Drupal\node\Entity\Node|null
   *   An instance of the node.
   */
  public function loadNode($nid) {
    $node = NULL;

    $node_storage = $this->getEntityStorage('node');
    $node = $node_storage->load($nid);

    return /* @var $node \Drupal\node\Entity\Node */ $node;
  }

  /**
   * Accepts a User ID and returns the user loaded from storage.
   *
   * @param int|string $uid
   *   The id of the user.
   *
   * @return \Drupal\user\Entity\User|null
   *   An instance of the user.
   */
  public function loadUser($uid) {
    $user = NULL;

    $user_storage = $this->getEntityStorage('user');
    $user = $user_storage->load($uid);

    return /* @var $user \Drupal\user\Entity\User */ $user;
  }

  /**
   * Returns the currently logged in user.
   *
   * @return \Drupal\Core\Session\AccountProxyInterface
   *   The current user object.
   */
  public function getCurrentUser() {
    return $this->currentUser;
  }

  /**
   * Returns the DateFormatterInterface service.
   *
   * @return \Drupal\Core\Datetime\DateFormatterInterface
   *   An instance of the DateFormatterInterface service.
   */
  public function getDateFormatter() {
    return $this->dateFormatter;
  }

  /**
   * Returns the core service UrlGenerator.
   *
   * @return \Drupal\Core\Routing\UrlGeneratorInterface
   *   The core service UrlGenerator.
   */
  public function getUrlGenerator() {
    return $this->urlGenerator;
  }

  /**
   * Accepts a route name and optional parameters and returns a URL string.
   *
   * @param string $route
   *   The route name.
   * @param array $parameters
   *   (optional) The route parameters if applicable.
   *
   * @return string
   *   A URL string.
   */
  public function getUrlFromRoute($route, array $parameters = []) {
    $url = $this->urlGenerator->generateFromRoute($route, $parameters);

    return $url;
  }

  /**
   * Gets the Cache service.
   *
   * @return \Drupal\Core\Cache\CacheBackendInterface
   *   An instance of the CacheBackendInterface.
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * Gets an instance of the LoggerChannelFactory.
   *
   * @return \Drupal\Core\Logger\LoggerChannelFactoryInterface
   *   An instance of the LoggerChannelFactoryInterface.
   */
  public function getLoggerFactory() {
    return $this->loggerFactory;
  }

  /**
   * Sets a message in the Drupal log.
   *
   * @param string $module
   *   The name (channel) of the module setting the log message.
   * @param string $message
   *   The message to be set.
   * @param string $level
   *   (optional) The level of severity.
   * @param array $context
   *   (optional) Context.
   */
  public function setLogMessage($module, $message, $level = 'notice', array $context = []) {
    $this->loggerFactory->get($module)->log($level, $message, $context);
  }

  /**
   * Returns a Config object.
   *
   * @param string $config_name
   *   The string name of the Config.
   *
   * @return \Drupal\Core\Config\Config
   *   The Config object.
   */
  public function getConfig($config_name) {
    $config = $this->configFactory->get($config_name);
    return $config;
  }

  /**
   * Returns an Editable Config object.
   *
   * @param string $config_name
   *   The string name of the Editable Config.
   *
   * @return \Drupal\Core\Config\Config
   *   The Config object.
   */
  public function getEditableConfig($config_name) {
    $editableConfig = $this->configFactory->getEditable($config_name);
    return $editableConfig;
  }

  /**
   * Gets/creates the given field storage.
   *
   * @param string $type
   *   The entity type.
   * @param string $field_name
   *   The name of the field.
   * @param string $field_type
   *   The type of field.
   * @param bool $multiple
   *   If the field can have multiple values.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\field\Entity\FieldStorageConfig
   *   A field storage config.
   */
  public function getFieldStorage($type, $field_name, $field_type, $multiple) {
    $field_storage = FieldStorageConfig::loadByName($type, $field_name);

    if (empty($field_storage)) {
      $field_storage_config_array = [
        'field_name' => $field_name,
        'langcode' => 'en',
        'entity_type' => $type,
        'settings' => [],
        'module' => 'athena_utility',
        'locked' => FALSE,
        'cardinality' => 1,
        'persist_with_no_fields' => FALSE,
        'custom_storage' => FALSE,
        'translatable' => FALSE,
        'type' => $field_type,
      ];

      if ($multiple) {
        $field_storage_config_array['cardinality'] = -1;
        $field_storage_config_array['settings'] = [
          'target_type' => 'node',
        ];
      }
      else {
        $field_storage_config_array['cardinality'] = 1;
      }

      $field_storage = FieldStorageConfig::create($field_storage_config_array);

      try {
        $field_storage->save();
      }
      catch (EntityStorageException $e) {
        $this->setLogMessage('athena_utility', 'Entity Storage Exception 7.', 'error');
      }
    }

    return $field_storage;
  }

  /**
   * Gets/creates the given field.
   *
   * @param string $type
   *   The entity type.
   * @param string $bundle
   *   The bundle type.
   * @param string $field_name
   *   The name of the field.
   * @param string $field_type
   *   The type of field.
   * @param bool $multiple
   *   (optional) If the field can have multiple values, defaults FALSE.
   * @param string $target_bundle
   *   (optional) The bundle type to reference by entity_reference fields.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\field\Entity\FieldConfig
   *   A field config.
   */
  public function getField($type, $bundle, $field_name, $field_type, $multiple = FALSE, $target_bundle = '') {
    if ($bundle != NULL) {
      $field = FieldConfig::loadByName($type, $bundle, $field_name);
    }
    else {
      $field = FieldConfig::loadByName($type, $type, $field_name);
    }

    if (empty($field)) {
      $field_storage = $this->getFieldStorage($type, $field_name, $field_type, $multiple);

      $field_config_array = [
        'field_name' => $field_name,
        'field_storage' => $field_storage,
        'label' => $field_name,
        'translatable' => FALSE,
        'langcode' => 'en',
        'field_type' => $field_type,
      ];

      if ($bundle != NULL) {
        $field_config_array['bundle'] = $bundle;
      }
      else {
        $field_config_array['bundle'] = $type;
      }

      if ($multiple) {
        $field_config_array['settings'] = [
          'target_type' => 'node',
          'handler' => 'default:node',
          'handler_settings' => [
            'target_bundles' => [
              $target_bundle,
            ],
            'auto_create' => FALSE,
          ],
        ];
      }

      $field = FieldConfig::create($field_config_array);

      $entity_view_display_storage = $this->getEntityStorage('entity_view_display');
      if ($bundle != NULL) {
        /* @var $entity_view_display_default \Drupal\Core\Entity\Display\EntityViewDisplayInterface */
        $entity_view_display_default = $entity_view_display_storage->load($type . '.' . $bundle . '.' . 'default');
      }
      else {
        $entity_view_display_default = $entity_view_display_storage->load($type . '.' . $type . '.' . 'default');
      }
      if ($entity_view_display_default != NULL) {
        if ($multiple) {
          $entity_view_display_default->setComponent($field_name, [
            'label' => 'above',
            'type' => 'entity_reference_label',
          ]);
        }
        else {
          $entity_view_display_default->setComponent($field_name, [
            'label' => 'above',
            'type' => 'text_default',
          ]);
        }
        if ($bundle != NULL) {
          /* @var $entity_view_display_teaser \Drupal\Core\Entity\Display\EntityViewDisplayInterface */
          $entity_view_display_teaser = $entity_view_display_storage->load($type . '.' . $bundle . '.' . 'teaser');
          if ($multiple) {
            $entity_view_display_teaser->setComponent($field_name, [
              'label' => 'above',
              'type' => 'entity_reference_label',
            ]);
          }
          else {
            $entity_view_display_teaser->setComponent($field_name, [
              'label' => 'above',
              'type' => 'text_default',
            ]);
          }
        }
      }
      $entity_form_display_storage = $this->getEntityStorage('entity_form_display');
      if ($bundle != NULL) {
        /* @var $entity_form_display_default \Drupal\Core\Entity\Display\EntityFormDisplayInterface */
        $entity_form_display_default = $entity_form_display_storage->load($type . '.' . $bundle . '.' . 'default');
      }
      else {
        $entity_form_display_default = $entity_form_display_storage->load($type . '.' . $type . '.' . 'default');
      }
      if ($entity_form_display_default != NULL) {
        if ($multiple) {
          $entity_form_display_default->setComponent($field_name, [
            'label' => 'above',
            'type' => 'entity_reference_autocomplete',
          ]);
        }
        else {
          $entity_form_display_default->setComponent($field_name, [
            'label' => 'above',
            'type' => 'string_textfield',
          ]);
        }
      }

      try {
        $field->save();
        if (isset($entity_view_display_default) && $entity_view_display_default != NULL) {
          $entity_view_display_default->save();
        }
        if (isset($entity_view_display_teaser) && $entity_view_display_teaser != NULL) {
          if ($bundle != NULL) {
            $entity_view_display_teaser->save();
          }
        }
        if (isset($entity_form_display_default) && $entity_form_display_default != NULL) {
          $entity_form_display_default->save();
        }
      }
      catch (EntityStorageException $e) {
        $this->setLogMessage('athena_utility', 'Entity Storage Exception 1.', 'error');
      }
    }

    return $field;
  }

  /**
   * Gets a formatted field name from given parent bundle and field.
   *
   * @param string $field_name
   *   The field name.
   * @param string $top_bundle
   *   The parent bundle.
   *
   * @return string
   *   A formatted field name.
   */
  public function getFormattedFieldName($field_name, $top_bundle) {
    $formatted_field_name = '';

    if (strpos($top_bundle, '_')) {
      $top_bundles = explode('_', $top_bundle);

      foreach ($top_bundles as $tb) {
        $formatted_field_name .= substr($tb, 0, 1) . '_';
      }

      $formatted_field_name .= $field_name;
    }
    else {
      $formatted_field_name = substr($top_bundle, 0, 1) . '_' . $field_name;
    }

    if (strlen($formatted_field_name) > 32) {
      $formatted_field_name = substr($formatted_field_name, 0, 32);
    }

    $formatted_field_name = strtolower($formatted_field_name);
    $formatted_field_name = preg_replace("/[^A-Za-z0-9_]/", '', $formatted_field_name);
    $formatted_field_name = trim($formatted_field_name);

    return $formatted_field_name;
  }

  /**
   * Gets a formatted bundle name for the given parent bundle and child.
   *
   * @param string $top_bundle
   *   Parent bundle name.
   * @param string $child
   *   Child bundle name.
   *
   * @return string
   *   A formatted bundle name.
   */
  public function getFormattedBundleName($top_bundle, $child) {
    $bundle_name = '';

    $child = preg_replace('/([A-Z])/', '_$1', $child);

    if (strpos($top_bundle, '_')) {
      $top_bundles = explode('_', $top_bundle);

      foreach ($top_bundles as $tb) {
        $bundle_name .= substr($tb, 0, 1) . '_';
      }

      $bundle_name .= $child;
    }
    else {
      $bundle_name = substr($top_bundle, 0, 1) . '_' . $child;
    }

    if (strlen($bundle_name) > 32) {
      $bundle_name = substr($bundle_name, 0, 32);
    }

    $bundle_name = strtolower($bundle_name);
    $bundle_name = preg_replace("/[^A-Za-z0-9_]/", '', $bundle_name);
    $bundle_name = trim($bundle_name);

    return $bundle_name;
  }

  /**
   * Gets/creates a content type.
   *
   * @param string $bundle
   *   The name of the bundle.
   * @param string $description
   *   (optional) A description of the bundle.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   An entity interface.
   */
  public function getContentType($bundle, $description = '') {
    $node_type_storage = $this->getEntityStorage('node_type');
    $content_type = $node_type_storage->load($bundle);

    if (empty($content_type)) {
      $content_type = $node_type_storage->create([
        'type' => $bundle,
        'name' => $bundle,
        'revision' => FALSE,
        'langcode' => 'en',
        'status' => TRUE,
        'description' => $description,
      ]);

      $entity_view_display_storage = $this->getEntityStorage('entity_view_display');
      $entity_view_display_default = $entity_view_display_storage->create([
        'targetEntityType' => 'node',
        'bundle' => $bundle,
        'mode' => 'default',
        'status' => TRUE,
      ]);

      $entity_view_display_teaser = $entity_view_display_storage->create([
        'targetEntityType' => 'node',
        'bundle' => $bundle,
        'mode' => 'teaser',
        'status' => TRUE,
      ]);

      $entity_form_display_storage = $this->getEntityStorage('entity_form_display');
      $entity_form_display_default = $entity_form_display_storage->create([
        'targetEntityType' => 'node',
        'bundle' => $bundle,
        'mode' => 'default',
        'status' => TRUE,
      ]);

      try {
        $content_type->save();
        $entity_view_display_default->save();
        $entity_view_display_teaser->save();
        $entity_form_display_default->save();
      }
      catch (EntityStorageException $e) {
        $this->setLogMessage('athena_utility', 'Entity Storage Exception 2.', 'error');
      }
    }

    return $content_type;
  }

  /**
   * Recursively creates fields, field storages, content types, and content.
   *
   * @param string|int $uid
   *   The uid of the author.
   * @param mixed $data
   *   The data to create content from.
   * @param string $top_bundle
   *   The parent bundle.
   * @param bool $create_configs
   *   If configs should be loaded/created.
   *
   * @return \Drupal\node\Entity\Node|null
   *   A node object, or NULL.
   */
  public function createRecursiveContent($uid, $data, $top_bundle, $create_configs = FALSE) {
    $node_storage = $this->getEntityStorage('node');
    $node_create_array = [];
    $node = NULL;
    if ($create_configs) {
      $this->getContentType($top_bundle);
    }

    if (is_array($data) && !empty($data)) {
      $node_create_array = [
        'uid' => $uid,
        'status' => 1,
        'title' => $top_bundle . '-' . microtime(),
        'type' => $top_bundle,
        'promoted' => 0,
        'langcode' => 'en',
      ];

      foreach ($data as $key => $val) {
        if (is_array($val) && !empty($val)) {
          $entity_reference_field_name = $this->getFormattedFieldName($key, $top_bundle);
          $referenced_content_type = $this->getFormattedBundleName($top_bundle, $key);

          if ($create_configs) {
            $this->getContentType($referenced_content_type, $top_bundle . ' - ' . $key);
            $this->getField('node', $top_bundle, $entity_reference_field_name, 'entity_reference', TRUE, $referenced_content_type);
          }

          $referenced_node = $this->createRecursiveContent($uid, $val, $referenced_content_type);

          $node_create_array[$entity_reference_field_name][] = $referenced_node;
        }
        elseif (!is_array($val)) {
          $formatted_field_name = $this->getFormattedFieldName($key, $top_bundle);
          if ($create_configs) {
            $field_type = $this->getFieldType($val);
            $this->getField('node', $top_bundle, $formatted_field_name, $field_type);
          }
          $node_create_array[$formatted_field_name] = $val;
        }
      }
    }

    if (!empty($node_create_array)) {
      try {
        /* @var $node \Drupal\node\Entity\Node */
        $node = $node_storage->create($node_create_array);
        $node->save();
        $node = $this->loadNode($node->id());
      }
      catch (EntityStorageException $e) {
        $this->setLogMessage('athena_utility', 'Entity Storage Exception 6.', 'error');
      }
    }

    return $node;
  }

  /**
   * Gets the correct field type for the given value.
   *
   * @param mixed $val
   *   A mixed value.
   *
   * @return string
   *   The type of field.
   */
  public function getFieldType($val) {
    if (is_bool($val)) {
      $field_type = 'boolean';
    }
    else {
      $field_type = 'string_long';
    }

    return $field_type;
  }

}
