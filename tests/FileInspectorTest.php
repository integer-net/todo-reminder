<?php

namespace IntegerNet\TodoReminder;

use Gitonomy\Git\Repository;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use function sys_get_temp_dir;
use PhpParser\ParserFactory;

class FileInspectorTest extends TestCase
{
    /**
     * @var string
     */
    private $pathToTestRepo;
    /**
     * @var Repository
     */
    private $repository;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var FileInspector
     */
    private $fileInspector;

    protected function setUp()
    {
        $this->pathToTestRepo = sys_get_temp_dir() . '/' . uniqid('grumphp-todo-reminder-test-', true);
        $this->createTestGitRepository($this->pathToTestRepo);
        $this->parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $this->fileInspector = new FileInspector($this->repository, $this->parser);
    }
    protected function tearDown()
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) !== 0) {
            `rm -rf $this->pathToTestRepo`;
        }
    }

    public function testFindTodoComment()
    {
        $fileName = 'test.php';
        $this->addFileToGitAndCommit(
            $fileName,
            <<<'PHP'
<?php
/**
 * Regular comment
 */
function f() {
  //TODO implement
}
PHP
        );
        $todoComments = $this->fileInspector->findTodoComments($fileName);
        $this->assertEquals(
            [
                new TodoComment(
                    $fileName,
                    6,
                    '//TODO implement',
                    0
                )
            ],
            $todoComments->getArrayCopy()
        );
    }

    public function testCountCommitsWithTodoComment()
    {
        $fileName = 'test.php';
        $this->addFileToGitAndCommit(
            $fileName,
            <<<'PHP'
<?php
/**
 * Regular comment
 */
function f() {
  //TODO implement
}
PHP
        );
        $this->addFileToGitAndCommit('innocent-commit.php', '<?php');
        $this->addFileToGitAndCommit('another-innocent-commit.php', '<?php');
        $this->addFileToGitAndCommit(
            $fileName,
            <<<'PHP'
<?php
/**
 * Regular comment
 */
function f() {
  //TODO implement
}
function g() {
  echo 'We still did not implemented the TODO';
}
PHP
        );
        $todoComments = $this->fileInspector->findTodoComments($fileName);
        $this->assertEquals(
            [
                new TodoComment(
                    $fileName,
                    6,
                    '//TODO implement',
                    1
                )
            ],
            $todoComments->getArrayCopy()
        );
    }

    public function testCountCommitsWithModifiedTodoComment()
    {
        $this->markTestIncomplete('TODO: test that changed TODO comments are traced back');
    }

    private function createTestGitRepository($path)
    {
        mkdir($path . '/.git', 0700, true);
        $this->repository = new Repository($path);
        $this->repository->run('init');
    }

    private function addFileToGitAndCommit($name, $content)
    {
        file_put_contents($this->pathToTestRepo . '/' . $name, $content);
        $this->repository->run('add', [$name]);
        $this->repository->run('commit', ['-m', 'First commit']);
    }
}
