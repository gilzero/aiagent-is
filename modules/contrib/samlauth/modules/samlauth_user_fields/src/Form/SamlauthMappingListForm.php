<?php

namespace Drupal\samlauth_user_fields\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Url;
use Drupal\samlauth\Controller\SamlController;
use Drupal\samlauth_user_fields\EventSubscriber\UserFieldsEventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Displays the list of attribute-field mappings; edits related configuration.
 */
class SamlauthMappingListForm extends ConfigFormBase {

  /**
   * The entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->entityFieldManager = $container->get('entity_field.manager');
    $instance->renderer = $container->get('renderer');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [UserFieldsEventSubscriber::CONFIG_OBJECT_NAME];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'samlauth_user_fields_edit_form';
  }

  /**
   * Form for adding or editing a mapping.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param int $mapping_id
   *   (optional) The numeric ID of the mapping.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $mapping_id = NULL) {
    $config = $this->config(UserFieldsEventSubscriber::CONFIG_OBJECT_NAME);

    // The bulk of this page is not a form at all, but a table. We're putting
    // that on the same page as the form options, because we have only two
    // checkboxes - which govern behavior related to the total of those table
    // rows. If this configuration form somehow grows, we'll split the table +
    // form off into separate pages/routes.
    $mappings = $config->get('field_mappings');
    $form = $this->listMappings(is_array($mappings) ? $mappings : []);

    if ($this->config(SamlController::CONFIG_OBJECT_NAME)->get('map_users')) {
      $form['config'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Configuration for linking'),
      ];

      $form['config']['link_first_user'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Link first user if multiple found'),
        '#description' => $this->t("If a link attempt matches multiple/'duplicate' users, link the first one and ignore the others. By default, login is denied and a Drupal administrator needs to decide what to do. (This never happens if matching is done on unique fields only, which is hopefully the case.)"),
        '#default_value' => $config->get('link_first_user'),
      ];

      $form['config']['ignore_blocked'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Ignore blocked users'),
        '#description' => $this->t("Never match/link blocked users. This may result in creating new users equal to a blocked user and granting them access - but enabling it (temporarily?) could help linking a correct user if 'duplicates' are matched. By default, if a blocked user is matched, it is linked then denied access."),
        '#default_value' => $config->get('ignore_blocked'),
      ];
    }

    // @todo Do we also want a "Configuration for synchronization" section with
    //   one checkbox "Only take action on first login", like we have for roles?
    //   We also have separate checkboxes (but the inverse) for the name and
    //   email values. We could implement this option per field, but would that
    //   be overkill?
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(UserFieldsEventSubscriber::CONFIG_OBJECT_NAME)
      ->set('link_first_user', $form_state->getValue('link_first_user'))
      ->set('ignore_blocked', $form_state->getValue('ignore_blocked'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Returns the list of attribute-field mappings.
   *
   * @param array $mappings
   *   The attribute-field mappings.
   *
   * @return array
   *   A renderable content array.
   */
  public function listMappings(array $mappings) {
    $linking_enabled = $this->config(SamlController::CONFIG_OBJECT_NAME)->get('map_users');

    $output['table'] = [
      '#theme' => 'table',
      '#header' => [
        $this->t('SAML Attribute'),
        $this->t('User Field'),
        $this->t('Operations'),
      ],
      '#sticky' => TRUE,
      '#empty' => $this->t("There are no mappings. You can add one using the link above."),
    ];
    if ($linking_enabled) {
      array_splice($output['table']['#header'], 2, 0, [$this->t('Use for linking')]);
    }

    if ($mappings) {
      $fields = $this->entityFieldManager->getFieldDefinitions('user', 'user');
      // We're identifying individual mappings by their numeric indexes in the
      // configuration value (which is defined as a 'sequence' in the config
      // schema). These are not renumbered while saving a mapping, so the
      // danger of using them is acceptable. (URLs would only pointing to a
      // different mapping if we delete the highest numbered mapping and re-add
      // one. Maybe things are renumbered arter exporting configuration, I
      // haven't tested, but that's also an acceptable risk.)
      foreach ($mappings as $id => $mapping) {
        $operations = [
          '#type' => 'dropbutton',
          '#links' => [
            'edit' => [
              'title' => $this->t('edit'),
              'url' => Url::fromRoute('samlauth_user_fields.edit', ['mapping_id' => $id]),
            ],
            'delete' => [
              'title' => $this->t('delete'),
              'url' => Url::fromRoute('samlauth_user_fields.delete', ['mapping_id' => $id]),
            ],
          ],
        ];

        $real_field_name = strstr($mapping['field_name'], ':', TRUE);
        if ($real_field_name) {
          $sub_field_name = substr($mapping['field_name'], strlen($real_field_name) + 1);
          if (isset($fields[$real_field_name])) {
            $property_definitions = $fields[$real_field_name]->getFieldStorageDefinition()->getPropertyDefinitions();
            if (isset($property_definitions[$sub_field_name])
                && $property_definitions[$sub_field_name] instanceof DataDefinition) {
              $sub_field_name = $property_definitions[$sub_field_name]->getLabel();
            }
          }
        }
        else {
          $real_field_name = $mapping['field_name'];
          $sub_field_name = '';
        }
        $user_field = (isset($fields[$real_field_name])
            ? $fields[$real_field_name]->getLabel()
            : $this->t('Unknown field %name', ['%name' => $real_field_name]))
          . ($sub_field_name ? ": $sub_field_name" : '');
        $output['table']['#rows'][$id] = [
          $mapping['attribute_name'],
          $user_field,
          $this->renderer->render($operations),
        ];
        if ($linking_enabled) {
          array_splice($output['table']['#rows'][$id], 2, 0, [$mapping['link_user_order'] ?? '']);
        }
      }
    }

    return $output;
  }

}
