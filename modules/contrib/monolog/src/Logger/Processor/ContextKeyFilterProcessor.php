<?php

declare(strict_types=1);

namespace Drupal\monolog\Logger\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

/**
 * Processor that filters out context keys.
 */
class ContextKeyFilterProcessor implements ProcessorInterface {

  /**
   * The context keys to filter.
   *
   * @var string[]
   */
  protected array $contextKeys;

  /**
   * ContextKeyFilterProcessor constructor.
   *
   * @param string[] $contextKeys
   *   The context keys to skip.
   */
  public function __construct(array $contextKeys = []) {
    $this->contextKeys = $contextKeys;
  }

  /**
   * {@inheritdoc}
   */
  public function __invoke(LogRecord $record): LogRecord {
    foreach ($this->contextKeys as $key) {
      if (isset($record['context'][$key])) {
        $backup = $record->toArray();
        unset($backup['context'][$key]);
        $record = $record->with(context: $backup['context']);
      }
    }

    return $record;
  }

}
