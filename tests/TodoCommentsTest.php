<?php

namespace IntegerNet\TodoReminder;

use PHPUnit\Framework\TestCase;

class TodoCommentsTest extends TestCase
{
    private $comment_1;

    private $comment_2;

    private $comment_3;

    protected function setUp(): void
    {
        $this->comment_1 = new TodoComment('file_1.php', 23, 'foo', 2);
        $this->comment_2 = new TodoComment('file_1.php', 27, 'bar', 4);
        $this->comment_3 = new TodoComment('file_2.php', 12, 'foo', 1);
    }

    public function testEmpty()
    {
        $this->assertFalse(
            (new TodoComments())->notEmpty()
        );
    }

    public function testNotEmpty()
    {
        $this->assertTrue(
            (new TodoComments($this->comment_1))->notEmpty()
        );
    }

    public function testMerge()
    {
        $comments = new TodoComments(
            $this->comment_1,
            $this->comment_2
        );
        $comments->add(
            new TodoComments(
                $this->comment_3
            )
        );
        $this->assertEquals(
            [
                $this->comment_1,
                $this->comment_2,
                $this->comment_3
            ],
            \iterator_to_array($comments)
        );
    }

    public function testFormat()
    {
        $comments = new TodoComments(
            new TodoComment('file1.php', 7, '//TODO implement', 3),
            new TodoComment('file1.php', 12, '//TODO implement', 3),
            new TodoComment(
                'file2.php',
                10,
                <<<PHP
/**
 * Some docblock
 *
 * @todo document
 */
PHP
                ,
                1
            )
        );
        $this->assertEquals(
            <<<'TXT'
The following TODO comments are NOT DONE:

file1.php
=========

Line 7:

//TODO implement

(NOT DONE since 3 commits on that file)

Line 12:

//TODO implement

(NOT DONE since 3 commits on that file)

file2.php
=========

Line 10:

/**
 * Some docblock
 *
 * @todo document
 */

(NOT DONE since 1 commit on that file)


TXT
            ,
            $comments->formatText()
        );
        $this->markTestIncomplete('TODO: expect different message if TODO was just introduced in this commit (0)');
    }
}
