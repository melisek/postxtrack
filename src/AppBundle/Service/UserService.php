<?php
/**
 * Created by PhpStorm.
 * User: hallgato
 * Date: 2016.11.28.
 * Time: 13:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\User_Address;
use Doctrine\ORM\EntityManager;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements IUserService
{
    private $entityManager;
    private $formFactory;

    private $usersRepo;
    private $usersAddrRepo;

    /**
     * UserService constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager=$entityManager;
        $this->formFactory=$formFactory;

        $this->usersRepo=$entityManager->getRepository(User::class);
        $this->usersAddrRepo=$entityManager->getRepository(User_Address::class);
    }

    /**
     * @param $userId int
     * @return User
     */
    public function getUserById($userId)
    {
        return $this->usersRepo->find($userId);
    }

    /**
     * @param $email string
     * @return User
     */
    public function getUserByEmail($email)
    {
        return $this->usersRepo->findBy(array( 'email' => $email ));
    }

    /**
     * @param $userId int
     * @return User_Address[]
     */
    public function getUserAddressesById($userId)
    {
        return $this->usersAddrRepo->findBy(array( 'user' => $userId ));
    }


}