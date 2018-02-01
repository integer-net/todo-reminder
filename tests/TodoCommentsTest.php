<?php

namespace IntegerNet\TodoBlame;

use PHPUnit\Framework\TestCase;

class TodoCommentsTest extends TestCase
{
    private $comment_1;

    private $comment_2;

    private $comment_3;

    protected function setUp()
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
}
