<?php

namespace Indentor\Html;

/**
 * @package Indentor\Html
 */
class WhiteSpaceRemover
{
    /**
     * @param string $html
     * @return string
     */
    public function removeWhiteSpaces($html)
    {
        $html = preg_replace("/([ ]{2,})/i", " ", $html);
        $html = preg_replace("/(\n|\t)/i", "", $html);

        return $html;
    }
}
