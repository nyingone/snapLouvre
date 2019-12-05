<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $currency;

    /**
     * AppExtension constructor.
     * @param string $currency
     */
    public function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price' , [$this, 'filterPrice']),
        ];
    }

    /**
     * @param string $number
     * @param int|null $decimals
     */
    public function filterPrice(string $number, ?int $decimals = 2)
    {
       return  number_format($number / 100 , $decimals) . ' ' . $this->currency;
    }
}