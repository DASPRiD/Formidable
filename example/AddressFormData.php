<?php
declare(strict_types = 1);

final class AddressFormData
{
    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $country;

    public function __construct(string $city, string $country)
    {
        $this->city = $city;
        $this->country = $country;
    }
}
