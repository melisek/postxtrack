<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;

class ServiceFactory
{
    private $entityManager;
    private $formFactory;

    /**
     * ServiceFactory constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }
    /**
     * @return IUserService
     */
    public function getUserService()
    {
        return new UserService($this->entityManager, $this->formFactory);
    }

    /**
     * @return IDepotService
     */
    public function getDepotService()
    {
        return new DepotService($this->entityManager, $this->formFactory);
    }

    /**
     * @return IParcelService
     */
    public function getParcelService()
    {
        return new ParcelService($this->entityManager, $this->formFactory);
    }

    /**
     * @return IStatusService
     */
    public function getStatusService()
    {
        return new StatusService($this->entityManager, $this->formFactory);
    }

    /**
     * @return IAddressService
     */
    public function getAddressService()
    {
        return new AddressService($this->entityManager, $this->formFactory);
    }
}