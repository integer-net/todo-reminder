<?php

namespace IntegerNet\TodoBlame;

use GrumPHP\Configuration\GrumPHP;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GrumPhpExtensionTest extends TestCase
{
    public function testIsRegistered()
    {
        $extension = new GrumPhpExtension();
        $container = new ContainerBuilder;
        $container->set('config', new GrumPHP($container));
        $extension->load($container);
        $this->assertTrue($container->has('task.todo_blame'), 'Service task.todo_blame should be registered');
        $this->assertEquals(
            ['task.todo_blame' => [['config' => 'todo_blame']]],
            $container->findTaggedServiceIds('grumphp.task'),
            'Service task.todo_blame should be tagged as GrumPHP task with config option'
        );
    }
}
