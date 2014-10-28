<?php

namespace Indenter;

/**
 * @package Indenter
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Parser
     */
    private $parser;

    /**
     *
     */
    public function setUp()
    {
        $this->parser = new Parser();
    }

    /**
     *
     */
    public function testMustParseTokenizedString()
    {
        $this->assertEquals(
            "<script></script>" . PHP_EOL .
            "<div>" . PHP_EOL .
            "    <p></p>" . PHP_EOL .
            "</div>",
            $this->parser->parse(
                "=<script></script>" . PHP_EOL .
                "+<div>" . PHP_EOL .
                "=<p></p>" . PHP_EOL .
                "-</div>"
            )
        );
    }
}
