<?php

namespace IntegerNet\TodoReminder;

class TodoComments extends \ArrayIterator
{
    public function __construct(TodoComment ...$comments)
    {
        parent::__construct($comments);
    }

    public function add(TodoComments $other): TodoComments
    {
        foreach ($other as $comment) {
            $this->append($comment);
        }
        return $this;
    }

    public function notEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function formatText(): string
    {
        $output = '';
        if ($this->notEmpty()) {
            $output .= "The following TODO comments are NOT DONE:\n\n";
            foreach ($this as $comment) {
                /** @var TodoComment $comment */
                if (!isset($last) || !$comment->isInSameFile($last)) {
                    $output .= $comment->file() . "\n";
                    $output .= str_repeat('=', mb_strlen($comment->file())) . "\n\n";
                }
                $output .= $comment->formatText();
                $last = $comment;
            }
        }
        return $output;
    }
}
