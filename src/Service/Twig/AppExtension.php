<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 03/08/2018
 * Time: 15:58
 */

namespace App\Service\Twig;


use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    public const NB_SUMMARY_CHAR = 170;

    public function getFilters()
    {
        return [
            new \Twig_Filter('shorten_text', function ($text) {


                $string = strip_tags($text);

                if (strlen($string) > self::NB_SUMMARY_CHAR) {


                    $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'-->';

                }

                # On retourne l'accroche
                return $string;

            }, array('is_safe' => array('html')))
        ];
    }
}