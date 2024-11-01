<?php

/**
 * @file
 * A database agnostic dump for testing purposes.
 *
 * This file was generated by the Drupal 9.2.6 db-tools.php script.
 */

use Drupal\Core\Database\Database;

$connection = Database::getConnection();

$connection->schema()->createTable('node_revision', [
  'fields' => [
    'nid' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
      'unsigned' => TRUE,
    ],
    'vid' => [
      'type' => 'serial',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ],
    'uid' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
    ],
    'title' => [
      'type' => 'varchar',
      'not null' => TRUE,
      'length' => '255',
      'default' => '',
    ],
    'log' => [
      'type' => 'text',
      'not null' => TRUE,
      'size' => 'big',
    ],
    'timestamp' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
    ],
    'status' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '1',
    ],
    'comment' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
    ],
    'promote' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
    ],
    'sticky' => [
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'default' => '0',
    ],
  ],
  'primary key' => [
    'vid',
  ],
  'indexes' => [
    'nid' => [
      'nid',
    ],
    'uid' => [
      'uid',
    ],
  ],
  'mysql_character_set' => 'utf8mb3',
]);

$connection->insert('node_revision')
  ->fields([
    'nid',
    'vid',
    'uid',
    'title',
    'log',
    'timestamp',
    'status',
    'comment',
    'promote',
    'sticky',
  ])
  ->values([
    'nid' => '1',
    'vid' => '1',
    'uid' => '1',
    'title' => 'Article one',
    'log' => '',
    'timestamp' => '1647492702',
    'status' => '0',
    'comment' => '2',
    'promote' => '1',
    'sticky' => '0',
  ])
  ->values([
    'nid' => '2',
    'vid' => '2',
    'uid' => '1',
    'title' => 'Article two',
    'log' => '',
    'timestamp' => '1647492753',
    'status' => '0',
    'comment' => '2',
    'promote' => '1',
    'sticky' => '0',
  ])
  ->values([
    'nid' => '3',
    'vid' => '3',
    'uid' => '1',
    'title' => 'Article three',
    'log' => '',
    'timestamp' => '1647492779',
    'status' => '1',
    'comment' => '2',
    'promote' => '1',
    'sticky' => '0',
  ])
  ->execute();
