<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $input = str_replace(" ", "-", $input);
        $input = preg_replace('#è|é|ê|ë#', 'e', $input);
        $input = preg_replace('#ç#', 'c', $input);
        $input = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $input);
        $input = preg_replace('#à|á|â|ã|ä|å#', 'a', $input);
        $input = preg_replace('#ì|í|î|ï#', 'i', $input);
        $input = preg_replace('#ù|ú|û|ü#', 'u', $input);
        $input = preg_replace('/-+/', '-', $input);
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        $input = strtolower($input);

        return trim($input, "-");
    }
}