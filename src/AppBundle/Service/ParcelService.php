<?php
/**
 * Created by PhpStorm.
 * User: hallgato
 * Date: 2016.11.28.
 * Time: 13:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\Depot;
use AppBundle\Entity\Parcel;
use AppBundle\Entity\Parcel_Status;
use AppBundle\Entity\Status;
use AppBundle\Entity\User;
use AppBundle\Entity\User_Address;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParcelService implements IParcelService
{
    private $entityManager;
    private $formFactory;

    private $parcelRepo;
    private $parcelStatusRepo;
    private $statusRepo;
    private $userRepo;
    private $userAddressRepo;
    private $depotRepo;

    private $userId;

    /**
     * ParcelService constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager=$entityManager;
        $this->formFactory=$formFactory;

        $this->parcelRepo=$entityManager->getRepository(Parcel::class);
        $this->parcelStatusRepo=$entityManager->getRepository(Parcel_Status::class);
        $this->statusRepo=$entityManager->getRepository(Status::class);
        $this->userRepo=$entityManager->getRepository(User::class);
        $this->userAddressRepo=$entityManager->getRepository(User_Address::class);
        $this->depotRepo=$entityManager->getRepository(Depot::class);
    }

    /**
     * @return Parcel[]
     */
    public function getParcels()
    {
        return $this->parcelRepo->findAll();
    }

    /**
     * @param $parcelId int
     * @return Parcel
     */
    public function getParcelById($parcelId)
    {
        $oneParcel = $this->parcelRepo->find($parcelId);
        if (!$oneParcel){
            throw new NotFoundHttpException("Parcel not found with ID {$parcelId}");
        }
        return $oneParcel;
    }

    /**
     * @return int
     */
    public function getMaxParcelId()
    {
        $max_id = $this->entityManager->createQueryBuilder()
            ->select('MAX(p.parcel_id)')
            ->from('AppBundle:Parcel', 'p')
            ->getQuery()
            ->getSingleScalarResult();
        return $max_id;
    }

    /**
     * @param $parcelCode string
     * @return Parcel[]
     */
    public function getParcelByCode($parcelCode)
    {
        $parcels = $this->parcelRepo->findBy( array ('parcel_code' => $parcelCode));
        return $parcels;
    }

    /**
     * @param $userId int
     * @param $direction boolean
     * @return Parcel[]
     */
    public function getParcelByUserId($userId, $direction)
    {
        if ($direction)
            $field = 'sender_address';
        else
            $field = 'rec_address';

        $userAddresses = $this->userAddressRepo->findBy(array( 'user' => $userId ));
        $parcels = $this->parcelRepo->findBy( array ($field => $userAddresses ));

        return $parcels;
    }

    /**
     * @param $parcel Parcel
     * @return Parcel_Status[]
     */
    public function getStatusesByParcel($parcel)
    {
        $statuses = $this->parcelStatusRepo->findBy(array('parcel' => $parcel));
        return $statuses;
    }

    /**
     * Return post parcel form
     * @param $oneParcel Parcel
     * @param $senderId int
     * @return FormInterface
     */
    public function getParcelPostForm($oneParcel, $senderId)
    {
        $this->userId = $senderId;

        $form = $this->formFactory->createBuilder(FormType::class, $oneParcel);

        $form->add('sender_address', EntityType::class, array(
            'class' => 'AppBundle:User_Address',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                    ->join('s.address', 'a')
                    ->where('s.user = :sid AND a.address_active = 1')
                    ->setParameter('sid', $this->userId);
            },
            'choice_label' => function ($senaddr) {
                return $senaddr->getAddress()->getAddressPostalCode() . ' ' . $senaddr->getAddress()->getAddressCountry() . ' ' .
                $senaddr->getAddress()->getAddressCity() . ' ' . $senaddr->getAddress()->getAddressStreet1();
            },
            'label' => 'Send from',
            'attr' => array( 'class' => 'form-control' )
        ));
        $form->add('rec_address', EntityType::class, array(
            'class' => 'AppBundle:User_Address',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('r')
                    ->join('r.address', 'a')
                    ->where('a.address_active = 1');
            },
            'choice_label' => function ($recaddr) {
                return $recaddr->getUser()->getEmail() . ' @ '. $recaddr->getAddress()->getAddressPostalCode() . ' ' . $recaddr->getAddress()->getAddressCountry() . ' ' .
                $recaddr->getAddress()->getAddressCity() . ' ' . $recaddr->getAddress()->getAddressStreet1();
            },
            'label' => 'Send to',
            'attr' => array( 'class' => 'form-control' )
        ));

        return $form->getForm();
    }

    /**
     * Return parcel status change form for admin users
     * @param $oneParcel Parcel
     * @return FormInterface
     */
    public function getParcelEditForm($oneParcel)
    {
        $form = $this->formFactory->createBuilder(FormType::class, $oneParcel);

        $form->add("parcel_code", TextType::class, array(
             'attr' => array( 'class' => 'form-control' )
            )
        );

        return $form->getForm();
    }

    /**
     * @param $oneParcel Parcel
     * @param $statusId int
     * @param $depotId int
     */
    public function saveParcelStatus($oneParcel, $statusId, $depotId)
    {
        $status = $this->statusRepo->find($statusId);
        $depot = $this->depotRepo->find($depotId);

        $parcelStatus = new Parcel_Status();
        $parcelStatus
            ->setParcel($oneParcel)
            ->setDepot($depot)
            ->setStatus($status);

        $this->entityManager->persist($parcelStatus);
        $this->entityManager->flush();
    }

    /**
     * @param $oneParcel Parcel
     */
    public function saveParcel($oneParcel)
    {
        $this->entityManager->persist($oneParcel);
        $this->entityManager->flush();
    }

}