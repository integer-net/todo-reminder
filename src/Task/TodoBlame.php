<?php
declare(strict_types=1);

namespace IntegerNet\TodoBlame\Task;

use GrumPHP\Configuration\GrumPHP;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\Context\ContextInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Task\TaskInterface;
use IntegerNet\TodoBlame\FileInspector;
use IntegerNet\TodoBlame\TodoComments;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class name is used in GrumPHP output, so it had to be moved to separate namespace instead of calling it GrumPhpTask
 */
class TodoBlame implements TaskInterface
{
    public const NAME = 'todo_blame';
    /**
     * @var GrumPHP
     */
    protected $grumPHP;
    /**
     * @param GrumPHP $grumPHP
     */
    public function __construct(GrumPHP $grumPHP)
    {
        $this->grumPHP = $grumPHP;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getConfiguration(): array
    {
        $configured = $this->grumPHP->getTaskConfiguration($this->getName());
        return $this->getConfigurableOptions()->resolve($configured);
    }

    public function getConfigurableOptions(): OptionsResolver
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefault(
            'triggered_by',
            ['php', 'phtml']
        );
        $optionsResolver->setAllowedTypes('triggered_by', 'array');
        return $optionsResolver;
    }

    public function canRunInContext(ContextInterface $context): bool
    {
        return $context instanceof RunContext || $context instanceof GitPreCommitContext;
    }

    public function run(ContextInterface $context): TaskResultInterface
    {
        $config = $this->getConfiguration();
        $files = $context->getFiles()->extensions($config['triggered_by']);
        if ($files->isEmpty()) {
            return TaskResult::createSkipped($this, $context);
        }
        $fileInspector = new FileInspector();
        $comments = new TodoComments();
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            $comments->add($fileInspector->findTodoComments((string)$file));
        }
        if ($comments->notEmpty()) {
            $this->output($comments->format());
            return TaskResult::createNonBlockingFailed($this, $context, 'I found unfinished TODOs!');
        }
        return TaskResult::createPassed($this, $context);
    }

    private function output(string $text)
    {
        echo $text;
    }
}
