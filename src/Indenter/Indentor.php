<?php

namespace Indenter;

/**
 * @package Indenter
 */
class Indenter
{

    /**
     * @param string $html
     * @return string
     */
    public function indent($html)
    {
        $lexer = new Lexer();
        $parser = new Parser();

        return $parser->parse($lexer->tokenize($html));
    }
}
