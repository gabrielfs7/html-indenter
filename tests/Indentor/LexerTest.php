<?php

namespace Indenter;

/**
 * @package Indenter
 */
class LexerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Lexer
     */
    private $lexer;

    /**
     *
     */
    public function setUp()
    {
        $this->lexer = new Lexer();
    }

    /**
     *
     */
    public function testMustReplaceHtmlOpenAndCloseTags()
    {
        $this->assertEquals(
            "+<div>" . PHP_EOL . "=" . PHP_EOL .
            "-</div>" . PHP_EOL . "=",
            $this->lexer->tokenize("<div></div>")
        );
    }

    /**
     *
     */
    public function testMustReplaceHtmlInlineTags()
    {
        $this->assertEquals(
            "=<script></script>",
            $this->lexer->tokenize("<script></script>")
        );
    }
}
