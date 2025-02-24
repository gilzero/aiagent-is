<?php

namespace Drupal\Tests\gnode\Functional;

use Drupal\Tests\group\Functional\EntityOperationsTest as GroupEntityOperationsTest;

/**
 * Tests that entity operations (do not) show up on the group overview.
 *
 * @see gnode_entity_operation()
 *
 * @group gnode
 */
class EntityOperationsTest extends GroupEntityOperationsTest {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['gnode'];

  /**
   * {@inheritdoc}
   */
  public function provideEntityOperationScenarios() {
    $scenarios['withoutAccess'] = [
      [],
      ['group/1/nodes' => 'Nodes'],
    ];

    $scenarios['withAccess'] = [
      [],
      ['group/1/nodes' => 'Nodes'],
      [
        'view group',
        'access group_node overview',
      ],
    ];

    $scenarios['withAccessAndViews'] = [
      ['group/1/nodes' => 'Nodes'],
      [],
      [
        'view group',
        'access group_node overview',
      ],
      ['views'],
    ];

    return $scenarios;
  }

}
