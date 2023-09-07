<?php

namespace App\Service;

class UtilitaireService
{


    /**
     * @param $chaine parameter
     *
     * @return array|string|string[]|null
     */
    public function makeSlug($chaine)
    {

        $accent
            = ['à',
               'â',
               'ä',
               'á',
               'ã',
               'å',
               'æ',
               'ç',
               'é',
               'è',
               'ê',
               'ë',
               'í',
               'ì',
               'î',
               'ï',
               'ñ',
               'ó',
               'ò',
               'ô',
               'ö',
               'õ',
               'ø',
               'œ',
               'ß',
               'ú',
               'ù',
               'û',
               'ü',
               'ý',
               'ÿ',
        ];

        $noAccent
            = ['a',
               'a',
               'a',
               'a',
               'a',
               'a',
               'ae',
               'c',
               'e',
               'e',
               'e',
               'e',
               'i',
               'i',
               'i',
               'i',
               'n',
               'o',
               'o',
               'o',
               'o',
               'o',
               'o',
               'oe',
               'ss',
               'u',
               'u',
               'u',
               'u',
               'y',
               'y',
        ];

        $newChaine = str_replace(
            $accent,
            $noAccent,
            strtolower($chaine)
        );

        return preg_replace('/[^a-zA-Z\d]/', '', $newChaine);

    }//end makeSlug()


}
