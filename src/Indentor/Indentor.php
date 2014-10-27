<?php

namespace Indentor;

/**
 * @package Indentor
 */
class Indentor
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
