<?php

namespace IntegerNet\TodoReminder;

use Gitonomy\Git\Repository;
use PhpParser\ParserFactory;

class Application
{
    public function run(): int
    {
        $options = $this->options();

        $repository = new Repository($options['root'] . '');
        $head = $repository->getHead();
        if ($head === null) {
            echo "TodoReminder cannot determine HEAD of repository";
            return 1;
        }
        $files = $head->getCommit()->getDiff()->getFiles();
        if (empty($files)) {
            echo "TodoReminder found no files to check";
            return 0;
        }
        $fileInspector = new FileInspector(
            $repository,
            (new ParserFactory())->create(ParserFactory::ONLY_PHP7)
        );
        $comments = new TodoComments();
        foreach ($files as $file) {
            /** @var \Gitonomy\Git\Diff\File $file */
            $fullPath = $repository->getWorkingDir() . '/' . $file->getName();
            if (file_exists($fullPath)) {
                $comments->add($fileInspector->findTodoComments($file->getName()));
            }
        }
        if ($comments->notEmpty()) {
            echo $comments->formatText(), "\n";
            return 0;
        }

        echo "TodoReminder has nothing to say.\n";
        return 0;
    }

    private function options(): array
    {
        $cliArguments = getopt('', ['root']);
        $defaults = [
            'root' => getcwd()
        ];
        return $cliArguments + $defaults;
    }
}
