<?php

namespace Drupal\sqlite\Driver\Database\sqlite;

use Drupal\Core\Database\Query\Update as QueryUpdate;

@trigger_error('Extending from \Drupal\sqlite\Driver\Database\sqlite\Update is deprecated in drupal:11.0.0 and is removed from drupal:12.0.0. Extend from the base class instead. See https://www.drupal.org/node/3256524', E_USER_DEPRECATED);

/**
 * SQLite implementation of \Drupal\Core\Database\Query\Update.
 */
class Update extends QueryUpdate {
}
