<?php

namespace Indentor;

use Indentor\Lexer\Token;
use Indentor\Html\WhiteSpaceRemover;

/**
 * @package Indentor
 *
 */
class Lexer
{

    /**
     * This tags will be put each in your own line.
     * @var array
     */
    private $inlineTags = [
        'b', 'big', 'i', 'tt', 'abbr', 'acronym', 'cite', 'code',
        'dfn', 'em', 'kbd', 'strong', 'samp', 'var', 'bdo', 'br',
        'sub', 'sup', '!doctype', 'title', 'script', 'link', 'meta',
        '!--', '--', 'iframe', 'button'
    ];

    /**
     * This tags will be simply ignored.
     * @var array
     */
    private $ignoredTags = [
        'img', 'input', 'small', 'del'
    ];

    /**
     * @param string $html
     * @return string
     */
    public function tokenize($html)
    {
        $whiteSpaceRemover = new WhiteSpaceRemover();

        $inlineHtml = $whiteSpaceRemover->removeWhiteSpaces($html);
        $inlineHtml = $this->tokenizeOpenBlocks($inlineHtml);
        $inlineHtml = $this->tokenizeCloseBlocks($inlineHtml);
        $inlineHtml = $this->tokenizeOpenInlineElements($inlineHtml);

        return $inlineHtml;
    }

    /**
     * @param string $html
     * @return string
     *
     * TODO refactory
     */
    private function tokenizeOpenBlocks($html)
    {
        $token = PHP_EOL . Token::TOKEN_INCREASE . "$0" . PHP_EOL . Token::TOKEN_EQUALS;

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
     *
     * TODO refactory
     */
    private function tokenizeCloseBlocks($html)
    {
        $token = PHP_EOL . Token::TOKEN_DECREASE . "$0" . PHP_EOL . Token::TOKEN_EQUALS;

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
     *
     * TODO refactory
     */
    private function tokenizeOpenInlineElements($html)
    {
        $token = PHP_EOL . Token::TOKEN_EQUALS . "$0";

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
