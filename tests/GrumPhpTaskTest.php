<?php

namespace IntegerNet\TodoBlame;

use GrumPHP\Collection\FilesCollection;
use GrumPHP\Configuration\ContainerFactory;
use GrumPHP\Configuration\GrumPHP;
use GrumPHP\Console\Command\Git\PreCommitCommand;
use GrumPHP\Locator\ConfigurationFile;
use GrumPHP\Runner\TaskResult;
use GrumPHP\Runner\TaskResultInterface;
use GrumPHP\Task\Context\GitPreCommitContext;
use GrumPHP\Task\Context\RunContext;
use GrumPHP\Util\Filesystem;
use IntegerNet\TodoBlame\Task\TodoBlame;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class GrumPhpTaskTest extends TestCase
{
    /**
     * @var TodoBlame
     */
    private $grumPhpTask;

    protected function setUp()
    {
        $this->grumPhpTask = new TodoBlame($this->createGrumPhpFromConfig());
    }

    public function testHasName()
    {
        $this->assertEquals('todo_blame', $this->grumPhpTask->getName());
    }

    public function testRunsInPreCommitContext()
    {
        $this->assertTrue(
            $this->grumPhpTask->canRunInContext(
                new GitPreCommitContext(new FilesCollection())
            )
        );
    }
    public function testRunsInRunContext()
    {
        $this->assertTrue(
            $this->grumPhpTask->canRunInContext(
                new RunContext(new FilesCollection())
            )
        );
    }

    public function testFileExtensionsCanBeConfigured()
    {
        $this->assertTrue(
            $this->grumPhpTask->getConfigurableOptions()->hasDefault('triggered_by'),
            'triggered_by configuration should exist'
        );
        $this->assertEquals(
            ['triggered_by' => ['php', 'phtml']],
            $this->grumPhpTask->getConfiguration(),
            'default configuration'
        );
    }

    public function testSkipIfNoFilesProvided()
    {
        $result = $this->grumPhpTask->run(
            new RunContext(new FilesCollection())
        );
        $this->assertEquals(TaskResult::SKIPPED, $result->getResultCode(), 'Result code should be SKIPPED');
    }

    public function testSkipIfNoPhpFilesProvided()
    {
        $result = $this->grumPhpTask->run(
            new RunContext(new FilesCollection(
                [
                    new \SplFileInfo('notphp.js')
                ]
            ))
        );
        $this->assertEquals(TaskResult::SKIPPED, $result->getResultCode(), 'Result code should be SKIPPED');
    }

    public function testPassWithEmptyPhpFile()
    {
        $pathToFile = sys_get_temp_dir() . '/empty.php';
        file_put_contents($pathToFile, '<?php ?>');
        $result = $this->grumPhpTask->run(
            new RunContext(new FilesCollection(
                [
                    new \SplFileInfo($pathToFile)
                ]
            ))
        );
        $this->assertEquals(TaskResult::PASSED, $result->getResultCode(), 'Result code should be PASSED');
    }

    public function testFailWithPhpFileThatContainsTodoComment()
    {
        $pathToFile = sys_get_temp_dir() . '/todo.php';
        file_put_contents($pathToFile, '<?php ' . "\n" . '//TODO catch me if you can' . "\n" . '?>');
        $result = $this->grumPhpTask->run(
            new RunContext(
                new FilesCollection(
                    [
                        new \SplFileInfo($pathToFile),
                    ]
                )
            )
        );
        $this->assertEquals(
            TaskResult::NONBLOCKING_FAILED,
            $result->getResultCode(),
            'Result code should be NONBLOCKING_FAILED'
        );
        $this->markTestIncomplete('TODO: Test detailed output');
    }

    private function createGrumPhpFromConfig(): GrumPHP
    {
        $dependencies = ContainerFactory::buildFromConfiguration(
            (new ConfigurationFile(new Filesystem()))->locate(__DIR__ . '/..')
        );
        return new GrumPHP($dependencies);
    }
}
