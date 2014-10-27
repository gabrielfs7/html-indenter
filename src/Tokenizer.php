<?php

namespace Indentor;

/**
 * @package Indentor
 *
 * TODO test
 */
class Tokenizer
{
    const TOKEN_INCREASE = '+';
    const TOKEN_DECREASE = '-';
    const TOKEN_EQUALS = '=';


    private $inlineTags = [
        'b', 'big', 'i', 'tt', 'abbr', 'acronym', 'cite', 'code',
        'dfn', 'em', 'kbd', 'strong', 'samp', 'var', 'bdo', 'br',
        'sub', 'sup', '!doctype', 'title', 'script', 'link', 'meta',
        '!--', '--', 'iframe', 'button'
    ];

    private $ignoredTags = [
        'img', 'input', 'small', 'del'
    ];

    /**
     * Replace blocks for a structure easier to parse
     */
    public function tokenize($html)
    {
        $html = $this->tokenizeOpenBlocks($html);
        $html = $this->tokenizeCloseBlocks($html);
        $html = $this->tokenizeOpenInlineElements($html);

        return $html;
    }

    /**
     * @param string $html
     * @return string
     */
    private function tokenizeOpenBlocks($html)
    {
        $token = PHP_EOL . self::TOKEN_INCREASE . "$0" . PHP_EOL . self::TOKEN_EQUALS;

        $elementsRegexp = $this->createTagsRegexp(
            array_merge($this->inlineTags, $this->ignoredTags)
        );

        $html = preg_replace("#<(?!" . $elementsRegexp . ")([^/][^>]*)?>#i", $token, $html);
        $html = preg_replace("#{% (?!extends|set|include).+ ([^%][^}]*) %}#i", $token, $html);

        return trim($html);
    }

    /**
     * @param string $html
     * @return string
     */
    private function tokenizeCloseBlocks($html)
    {
        $token = PHP_EOL . self::TOKEN_DECREASE . "$0" . PHP_EOL . self::TOKEN_EQUALS;

        $elementsRegexp = $this->createTagsRegexp(
            array_merge($this->inlineTags, $this->ignoredTags)
        );

        $html = preg_replace("#</(?!" . $elementsRegexp . ")([^>]*)>#i", $token, $html);
        $html = preg_replace("#{% (end\\w+) %}#i", $token, $html);

        return trim($html);
    }

    /**
     * @param string $html
     * @return string
     */
    private function tokenizeOpenInlineElements($html)
    {
        $token = PHP_EOL . self::TOKEN_EQUALS . "$0";

        $html = preg_replace("#<(" . implode('|', $this->inlineTags) . ")( [^>]*)?[/]?>#i", $token, $html);

        return trim($html);
    }

    /**
     * @param array $tags
     * @return string
     */
    private function createTagsRegexp(array $tags)
    {
        $elements = [];

        foreach ($tags as $tag) {
            $elements[] = $tag . '(?: |>)';
        }

        return implode('|', $elements);
    }
}
