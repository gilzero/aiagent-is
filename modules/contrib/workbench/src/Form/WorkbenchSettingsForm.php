<?php

namespace Drupal\workbench\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generates the Workbench configuration form.
 */
class WorkbenchSettingsForm extends ConfigFormBase {

  /**
   * The entity type manager service.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor for WorkbenchSettingsForm class.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
    $container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'workbench_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['workbench.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('workbench.settings');

    $form['help'] = [
      '#markup' => $this->t('Workbench provides three pages that can be configured. The overview "My Workbench" page has three content sections, while the "My edits" and "All recent content" pages have one. Select the View and display that you wish to use.'),
    ];

    foreach ($this->settingsItems() as $key => $label) {
      $form[$key] = [
        '#title' => $label,
        '#type' => 'select',
        '#options' => $this->getOptions(),
        '#default_value' => $config->get($key),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('workbench.settings');
    foreach ($this->settingsItems() as $key => $label) {
      $config->set($key, $form_state->getValue($key));
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Returns an array of settings items.
   */
  public function settingsItems() {
    return [
      'overview_left' => $this->t('Overview block left'),
      'overview_right' => $this->t('Overview block right'),
      'overview_main' => $this->t('Overview block main'),
      'edits_main' => $this->t('My edits main'),
      'all_main' => $this->t('All content main'),
    ];
  }

  /**
   * Gets a formatted list of all Views.
   */
  public function getOptions() {
    $views = $this->entityTypeManager->getStorage('view')->loadMultiple();
    foreach ($views as $view => $data) {
      $displays = $data->get('display');
      foreach ($displays as $display => $info) {
        $options[$view . ':' . $display] = $data->label() . ' : ' . $info['display_title'];
      }
    }
    return $options;
  }

}
