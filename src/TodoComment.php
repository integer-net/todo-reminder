<?php

namespace IntegerNet\TodoReminder;

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

    public function isInSameFile(TodoComment $other): bool
    {
        return $other->file === $this->file;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function formatText(): string
    {
        $commits = $this->notDoneSince === 1 ? 'commit' : 'commits';
        return "Line $this->lineNumber:

$this->comment

(NOT DONE since $this->notDoneSince $commits on that file)

";
    }
}
