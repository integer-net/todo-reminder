<?php

namespace IntegerNet\TodoBlame;

class FileInspector
{

    public function findTodoComments(string $filePath): TodoComments
    {
        //TODO real implementation
        if (strpos(file_get_contents($filePath), 'TODO') !== false) {
            return new TodoComments(
                new TodoComment($filePath, 0, 'TODO', 0)
            );
        }
        return new TodoComments();
    }

    private function findLineNumbers(string $filename): array
    {
        return [];
    }
}
