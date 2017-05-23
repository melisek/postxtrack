<?php

namespace AppBundle\Service;

use AppBundle\Entity\Address;
use Symfony\Component\Form\FormInterface;

interface IAddressService {

    /**
     * @param $onlyActive boolean
     * @return Depot[]
     */
    //public function getDepots($onlyActive);

    /**
     * @param $addressId int
     * @return Address
     */
    public function getAddressById($addressId);

    /**
     * @param $oneAddress Address
     * @param $userId int
     * @return array
     */
    public function checkAddressUser($oneAddress, $userId);

    /**
     * @param $oneAddress Address
     * @return FormInterface
     */
    public function getAddressForm($oneAddress);

    /**
     * @param $oneAddress Address
     */
    public function saveAddress($oneAddress);

    /**
     * @param $userId int
     * @param $addressId int
     */
    public function saveUserAddress($userId, $addressId);

    /**
     * @param $addressId int
     * @param $enable boolean
     */
    public function enableDisableAddress($addressId, $enable);

    /**
     * @param $depotId int
     */
    //public function deleteDepot($depotId);
}