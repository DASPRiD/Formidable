<?php
declare(strict_types = 1);

final class UserFormData
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var AddressFormData
     */
    private $address;

    /**
     * @var int[]
     */
    private $groupIds;

    public function __construct(string $name, AddressFormData $address, array $groupIds)
    {
        $this->name = $name;
        $this->address = $address;
        $this->groupIds = $groupIds;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAddress() : AddressFormData
    {
        return $this->address;
    }

    public function getGroupIds() : array
    {
        return $this->groupIds;
    }
}
