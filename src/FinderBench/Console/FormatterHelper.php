<?php

namespace FinderBench\Console;

use Symfony\Component\Console\Helper\FormatterHelper as BaseFormatterHelper;

/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 */
class FormatterHelper extends BaseFormatterHelper
{
    const ALIGN_LEFT  = 'left';
    const ALIGN_RIGHT = 'right';

    public function formatCell($text, $width, $align = self::ALIGN_LEFT)
    {
        $length = strlen($text);

        if ($length > $width) {
            switch ($align) {
                case self::ALIGN_LEFT:  return substr($text, $width);
                case self::ALIGN_RIGHT: return substr($text, $width-$length);
            }

            throw new \InvalidArgumentException('Unknown alignment: '.$align);
        }

        if ($width > $length) {
            $space = str_repeat(' ', $width - $length);

            switch ($align) {
                case self::ALIGN_LEFT:  return $text.$space;
                case self::ALIGN_RIGHT: return $space.$text;
            }

            throw new \InvalidArgumentException('Unknown alignment: '.$align);
        }

        return $text;
    }
}
