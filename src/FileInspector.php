<?php

namespace IntegerNet\TodoReminder;

use Gitonomy\Git\Repository;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;

class FileInspector
{
    /**
     * @var Repository
     */
    private $repository;
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Repository $repository, Parser $parser)
    {
        $this->repository = $repository;
        $this->parser = $parser;
    }

    /**
     *
     * Command line version, given $FILE and $LINE of comment:
     *
     * git blame -p -L $LINE,$LINE $FILE
     * => commit hash $COMMIT
     *
     * git log --oneline --follow -M $COMMIT..HEAD -- $FILE
     * => commits to file since $COMMIT
     *
     * wc
     * => count
     *
     *
     * @param string $filePath
     * @return TodoComments
     */
    public function findTodoComments(string $filePath): TodoComments
    {
        if (0 === strpos($filePath, $this->repository->getPath())) {
            $filePath = (string)substr($filePath, strlen($this->repository->getPath()));
        }
        $fullPath = $this->repository->getPath() . '/' . $filePath;
        $ast = $this->parser->parse(file_get_contents($fullPath));
        $traverser = new NodeTraverser();
        $commentVisitor = new class extends NodeVisitorAbstract
        {
            /**
             * @var Comment[]
             */
            public $comments = [];

            public function enterNode(Node $node)
            {
                $comments = $node->getAttribute('comments');
                foreach ((array)$comments as $comment) {
                    /** @var Comment $comment */
                    if (preg_match('{\bTODO\b}i', $comment->getText())) {
                        $this->comments[] = $comment;
                    }
                }
            }
        };
        $traverser->addVisitor($commentVisitor);
        $traverser->traverse($ast);

        $todoComments = new TodoComments();
        foreach ($commentVisitor->comments as $comment) {
            /** @var Comment $comment */
            $line = $comment->getLine();
            $introducedInCommit =
                $this->repository->getBlame('HEAD', $filePath, "$line,$line")->getLine(1)->getCommit();
            $notDoneSince =
                $this->repository->getLog([$introducedInCommit->getHash() . "..HEAD"], [$filePath])->count();
            $todoComments->append(
                new TodoComment(
                    $filePath,
                    $comment->getLine(),
                    $comment->getReformattedText(),
                    $notDoneSince
                )
            );
        }
        return $todoComments;
    }
}
