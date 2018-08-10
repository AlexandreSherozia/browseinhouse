<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 03/08/2018
 * Time: 15:58
 */

namespace App\Service\Twig;


use Gumlet\ImageResize;
use Gumlet\ImageResizeException;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    public const NB_SUMMARY_CHAR = 170;

    public function getFilters()
    {
        return [
            new \Twig_Filter('shorten_text', array($this, 'textFilter')),
            new \Twig_Filter('photo_encoder', array($this, 'photoEncoder')),
            ];
        }

        public function textFilter($text)
        {


                $string = strip_tags($text);

                if (strlen($string) > self::NB_SUMMARY_CHAR) {


                    $stringCut = substr($string, 0, self::NB_SUMMARY_CHAR);

                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';

                }

                # On retourne l'accroche
                return $string;

        }

        public function photoEncoder($photo)
        {
            try {
                $image = new ImageResize($photo);
                echo $image->scale(50);
                //$image->crop(100, 100, true, ImageResize::CROPCENTER);
                $image->save('thumbnail.jpg');
                $image->output();

            } catch (ImageResizeException $e) {
                return $e;
            }
            return $photo;
        }

    }
