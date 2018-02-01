<?php

namespace IntegerNet\TodoBlame;

use GrumPHP\Configuration\Compiler\TaskCompilerPass;
use GrumPHP\Extension\ExtensionInterface;
use IntegerNet\TodoBlame\Task\TodoBlame;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GrumPhpExtension implements ExtensionInterface
{
    public function load(ContainerBuilder $container)
    {
        $container->register('task.todo_blame', TodoBlame::class)
            ->addArgument($container->get('config'))
            ->addTag(TaskCompilerPass::TAG_GRUMPHP_TASK, ['config' => TodoBlame::NAME]);
    }
}
