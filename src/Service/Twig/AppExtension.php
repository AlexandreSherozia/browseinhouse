<?php

namespace App\Service\Twig;


use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public const NB_SUMMARY_CHAR = 170;

    public function getFilters()
    {
        return [
            new \Twig_Filter('shorten_text', array($this, 'textFilter')),
            new \Twig_Filter('text_trim', array($this, 'textTrim')),
        ];
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function textFilter(string $text)
    {
        $string = strip_tags($text);

        if (strlen($string) > self::NB_SUMMARY_CHAR) {

            $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
        }

        # On retourne l'accroche
        return $string;
    }

    public function textTrim($number)
    {
        return $number . " €";
    }
}

