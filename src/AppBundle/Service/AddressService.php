<?php
/**
 * Created by PhpStorm.
 * User: hallgato
 * Date: 2016.11.28.
 * Time: 13:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\Address;
use AppBundle\Entity\User_Address;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AddressService implements IAddressService
{
    private $entityManager;
    private $formFactory;

    private $addressRepo;
    private $userRepo;
    private $userAddressRepo;

    /**
     * AddressService constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager=$entityManager;
        $this->formFactory=$formFactory;

        $this->addressRepo=$entityManager->getRepository(Address::class);
        $this->userRepo=$entityManager->getRepository(User::class);
        $this->userAddressRepo=$entityManager->getRepository(User_Address::class);
    }

    /**
     * Get address by id
     * @param $addressId int
     * @return Address
     */
    public function getAddressById($addressId)
    {
        $oneAddress = $this->addressRepo->find($addressId);
        if (!$oneAddress){
            throw new NotFoundHttpException("Address not found with ID {$addressId}");
        }
        return $oneAddress;
    }

    /**
     * Check address owner
     * @param $oneAddress Address
     * @param $userId int
     * @return array
     */
    public function checkAddressUser($oneAddress, $userId)
    {
        // check if this address belongs to the user who wants to edit it :)
        $addressOwner = $this->userAddressRepo->createQueryBuilder('ua')
            ->join('ua.address', 'a')
            ->join('ua.user', 'u')
            ->where('u.user_id = :uid AND a.address_id= :aid')
            ->setParameter('uid', $userId)
            ->setParameter('aid', $oneAddress->getAddressId())
            ->getQuery()
            ->getResult();

        if (!$addressOwner){
            throw new AccessDeniedHttpException("Access denied.");
        }
        else
            return $addressOwner;
    }


    /**
     * Returning form for editing address
     * @param $oneAddress Address
     * @return FormInterface
     */
    public function getAddressForm($oneAddress)
    {

        $form = $this->formFactory->createBuilder(FormType::class, $oneAddress);

        $form->add("address_postal_code", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'Postal code'
            )
        );
        $form->add("address_country", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'Country',
                'required' => false
            )
        );
        $form->add("address_county", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'County/State',
                'required' => false
            )
        );
        $form->add("address_city", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'City',
                'required' => false
            )
        );
        $form->add("address_street1", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'Street line',
                'required' => false
            )
        );
        $form->add("address_street2", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'Street line 2',
                'required' => false
            )
        );
        $form->add("address_active", CheckboxType::class, array(
            'label' => 'Enable',
            'required' => false
        ));

        return $form->getForm();
    }

    /**
     * @param $oneAddress Address
     */
    public function saveAddress($oneAddress)
    {
        $this->entityManager->persist($oneAddress);
        $this->entityManager->flush();
    }

    /**
     * Connect new address to the logged in user
     * @param $userId int
     * @param $addressId int
     */
    public function saveUserAddress($userId, $addressId)
    {
        $user = $this->userRepo->find($userId);
        $address = $this->addressRepo->find($addressId);

        $userAddress = new User_Address();
        $userAddress
            ->setAddress($address)
            ->setUser($user);
        $this->entityManager->persist($userAddress);
        $this->entityManager->flush();
    }

    /**
     * Enable or disable address
     * @param $addressId int
     * @param $enable boolean
     */
    public function enableDisableAddress($addressId, $enable)
    {
        $oneAddress = $this->getAddressById($addressId);
        $oneAddress->setAddressActive($enable);
        $this->entityManager->flush();
    }


}