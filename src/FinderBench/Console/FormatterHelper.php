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

    private $numberFormatter;

    public function __construct()
    {
        $this->numberFormatter = new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE);
    }

    public function formatCell($text, $width, $align = self::ALIGN_LEFT, $style = null)
    {
        $length = strlen($text);

        if ($length > $width) {
            switch ($align) {
                case self::ALIGN_LEFT:  return $this->formatStyle(substr($text, $width), $style);
                case self::ALIGN_RIGHT: return $this->formatStyle(substr($text, $width-$length), $style);
            }

            throw new \InvalidArgumentException('Unknown alignment: '.$align);
        }

        if ($width > $length) {
            $space = str_repeat(' ', $width - $length);

            switch ($align) {
                case self::ALIGN_LEFT:  return $this->formatStyle($text.$space, $style);
                case self::ALIGN_RIGHT: return $this->formatStyle($space.$text, $style);
            }

            throw new \InvalidArgumentException('Unknown alignment: '.$align);
        }

        if (null !== $style) {
            $text = sprintf('<%s>%s</%s>', $style, $text, $style);
        }

        return $this->formatStyle($text, $style);
    }

    public function formatStyle($text, $style = null)
    {
        if (null !== $style) {
            return sprintf('<%s>%s</%s>', $style, $text, $style);
        }

        return $text;
    }

    public function formatNumber($number)
    {
        return $this->numberFormatter->format($number);
    }
}
