<?php

namespace AppBundle\Service;

use AppBundle\Entity\User_Address;
use AppBundle\Entity\User;

interface IUserService {

    /**
     * @param $userId int
     * @return User
     */
    public function getUserById($userId);

    /**
     * @param $email string
     * @return User
     */
    public function getUserByEmail($email);

    /**
     * @param $userId int
     * @return User_Address[]
     */
    public function getUserAddressesById($userId);

}