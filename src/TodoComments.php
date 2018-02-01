<?php

namespace IntegerNet\TodoBlame;

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

    public function format(): string
    {
        return '';
    }
}
