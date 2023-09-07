<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateHExtension extends AbstractExtension
{


    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {

        return [
            new TwigFilter('dateH',
                [$this,
                 'formatDate',
                ]
            ),
        ];
    }


    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {

        return [
            new TwigFunction('function_name',
                [$this,
                 'doSomething',
                ]
            ),
        ];
    }


    /**
     * @param DateTime|null $date parameter
     *
     * @return string|void
     */
    public function formatDate(?DateTime $date)
    {

        if ($date !== null) {

            $dateEurope = strtotime($date->format('Y-m-d H:i:s').' + 2 hours');

            $date->setTimestamp($dateEurope);

            return $date->format('d/m/Y à H').'h'.$date->format('i');
        }
    }


}
