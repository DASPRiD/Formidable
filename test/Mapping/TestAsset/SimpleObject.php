<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping\TestAsset;

class SimpleObject
{
    /**
     * @var string
     */
    private $foo;

    /**
     * @var string
     */
    private $bar;

    public function __construct(string $foo, string $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function getFoo() : string
    {
        return $this->foo;
    }

    public function getBar() : string
    {
        return $this->bar;
    }
}
