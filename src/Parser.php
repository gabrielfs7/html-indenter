<?php

namespace Indentor;

use Indentor\Lexer\Token;
use Indentor\Exception\NegativeIndentationLevelException;
use Indentor\Exception\UnknownTokenException;

/**
 * @package Indentor
 */
class Parser
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

        foreach (explode(Token::LINE_SEPARATOR, $html) as $lineContent) {
            $token = substr($lineContent, 0, 1);
            $lineContent = trim(substr($lineContent, 1));

            if (! $lineContent) {
                continue;
            }

            $nextIndentLevel = $indentLevel;

            try {
                $nextIndentLevel = $this->changeIndentLevelByToken($indentLevel, $token);

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
    private function changeIndentLevelByToken($indentLevel, $token)
    {
        switch ($token) {
            case Token::TOKEN_INCREASE:
                $indentLevel++;
                break;

            case Token::TOKEN_DECREASE:
                $indentLevel--;
                break;

            case Token::TOKEN_EQUALS:
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
