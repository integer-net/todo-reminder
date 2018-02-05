<?php

namespace IntegerNet\TodoReminder;

use Gitonomy\Git\Diff\File;
use Gitonomy\Git\Repository;
use PhpParser\ParserFactory;

class Application
{
    public function run()
    {
        $options = $this->options();

        $repository = new Repository($options['root'] . '');
        $files = $repository->getHead()->getCommit()->getDiff()->getFiles();
        if (empty($files)) {
            echo "No files to check";
            exit(0);
        }
        $fileInspector = new FileInspector(
            $repository,
            (new ParserFactory())->create(ParserFactory::ONLY_PHP7)
        );
        $comments = new TodoComments();
        foreach ($files as $file) {
            /** @var File $file */
            $fullPath = $repository->getWorkingDir() . '/' . $file->getName();
            if (file_exists($fullPath)) {
                $comments->add($fileInspector->findTodoComments($file->getName()));
            }
        }
        echo $comments->formatText();
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
