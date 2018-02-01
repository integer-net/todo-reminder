<?php

namespace IntegerNet\TodoBlame;

class TodoComment
{
    /**
     * @var string
     */
    private $file;
    /**
     * @var int
     */
    private $lineNumber;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var int
     */
    private $notDoneSince;

    public function __construct(string $file, int $lineNumber, string $comment, int $notDoneSince)
    {
        $this->file = $file;
        $this->lineNumber = $lineNumber;
        $this->comment = $comment;
        $this->notDoneSince = $notDoneSince;
    }
}
