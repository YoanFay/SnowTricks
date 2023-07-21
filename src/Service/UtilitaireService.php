<?php

namespace App\Service;

class UtilitaireService{

    function makeSlug($chaine) {
        // Supprimer les accents
        $chaine = str_replace(
            array('à', 'â', 'ä', 'á', 'ã', 'å', 'æ', 'ç', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ñ', 'ó', 'ò', 'ô', 'ö', 'õ', 'ø', 'œ', 'ß', 'ú', 'ù', 'û', 'ü', 'ý', 'ÿ'),
            array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'oe', 'ss', 'u', 'u', 'u', 'u', 'y', 'y'),
            strtolower($chaine)
        );

        $chaine = preg_replace('/[^a-zA-Z0-9]/', '', $chaine);

        return $chaine;
    }

}