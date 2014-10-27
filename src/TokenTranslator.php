<?php

namespace Indentor;

use Indentor\Exception\NegativeIndentationLevelException;
use Indentor\Exception\UnknownTokenException;

/**
 * @package Indentor
 *
 * TODO test
 */
class TokenTranslator
{
    private $indentString = '    ';

    /**
     * @param string $html
     * @throws \Exception
     * @return string
     */
    public function translate($html)
    {
        $outputHtml = "";
        $indentLevel = 0;

        foreach (explode(PHP_EOL, $html) as $lineContent) {
            $token = substr($lineContent, 0, 1);
            $lineContent = trim(substr($lineContent, 1));

            if (! $lineContent) {
                continue;
            }

            $nextIndentLevel = $indentLevel;

            try {
                $nextIndentLevel = $this->getIndentLevelByToken($indentLevel, $token);

            } catch (UnknownTokenException $exception) {
                //TODO and now?
            }

            $outputHtml .= str_repeat(
                $this->indentString,
                $nextIndentLevel < $indentLevel ? $nextIndentLevel : $indentLevel
            );

            $outputHtml .= $lineContent . PHP_EOL;
            $indentLevel = $nextIndentLevel;
        }

        return $outputHtml;
    }


    /**
     * @param string $indentLevel
     * @param string $token
     * @throws UnknownTokenException
     * @throws NegativeIndentationLevelException
     * @return string
     */
    private function getIndentLevelByToken($indentLevel, $token)
    {
        switch ($token) {
            case Tokenizer::TOKEN_INCREASE:
                $indentLevel++;
                break;

            case Tokenizer::TOKEN_DECREASE:
                $indentLevel--;
                break;

            case Tokenizer::TOKEN_EQUALS:
                break;

            default:
                throw new UnknownTokenException('Unknown token ' . $token);
                break;
        }

        if ($indentLevel < 0) {
            throw new NegativeIndentationLevelException(
                'Indentation level achieved a negative value'
            );
        }

        return $indentLevel;
    }
}
