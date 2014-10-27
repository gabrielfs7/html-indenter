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
        return $this->translate($this->tokenize($html));
    }

    /**
     * @param string $html
     * @return string
     */
    private function tokenize($html)
    {
        $tokenizer = new Tokenizer();

        return $tokenizer->tokenize($this->cleanupSpaces($html));
    }

    /**
     * @param string $html
     * @return string
     */
    private function translate($html)
    {
        $translator = new TokenTranslator();

        return $translator->translate($html);
    }

    /**
     * @param string $html
     * @return string
     */
    private function cleanupSpaces($html)
    {
        $html = preg_replace("/([ ]{2,})/i", " ", $html);
        $html = preg_replace("/(\n|\t)/i", "", $html);

        return $html;
    }
}
