<?php

namespace Drupal\schemata_json_schema\Normalizer\json;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Normalizer for FieldDefinitionInterface objects.
 *
 * This normalizes the variant of data fields particular to the Field system.
 * By accessing this via the FieldDefinitionInterface, there is greater access
 * to some of the methods providing deeper schema properties.
 */
class FieldDefinitionNormalizer extends ListDataDefinitionNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = FieldDefinitionInterface::class;

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject {
    /** @var \Drupal\Core\Field\FieldDefinitionInterface $entity */
    $normalized = parent::normalize($entity, $format, $context);

    // Specify non-contextual default value as an example.
    $default_value = $entity->getDefaultValueLiteral();
    if (!empty($default_value)) {
      $normalized['properties'][$context['name']]['default'] = $default_value;
    }

    // The cardinality is the configured maximum number of values the field can
    // contain. If unlimited, we do not include a maxItems attribute.
    $cardinality = $entity->getFieldStorageDefinition()->getCardinality();
    if ($cardinality != FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) {
      $normalized['properties'][$context['name']]['maxItems'] = $cardinality;
    }

    return $normalized;
  }

  /**
   *{@inheritdoc}
   */
  public function getSupportedTypes(?string $format): array {
    return [
      FieldDefinitionInterface::class => true,
    ];
  }

}
