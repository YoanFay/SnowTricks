<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateHExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
// If your filter generates SAFE HTML, you should add a third
// parameter: ['is_safe' => ['html']]
// Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('dateH', [$this, 'formatDate']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function formatDate($date)
    {

        if ($date !== null) {

            $dateEurope = strtotime($date->format('Y-m-d H:i:s').' + 2 hours');

            $date->setTimestamp($dateEurope);

            return $date->format('d/m/Y à H') . 'h' . $date->format('i');
        }
    }
}
