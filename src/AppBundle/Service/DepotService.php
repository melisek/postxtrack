<?php
/**
 * Created by PhpStorm.
 * User: hallgato
 * Date: 2016.11.28.
 * Time: 13:58
 */

namespace AppBundle\Service;

use AppBundle\Entity\Depot;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepotService implements IDepotService
{
    private $entityManager;
    private $formFactory;

    private $depotRepo;

    /**
     * DepotService constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager=$entityManager;
        $this->formFactory=$formFactory;

        $this->depotRepo=$entityManager->getRepository(Depot::class);
    }

    /**
     * Get all or only enabled depots
     * @param $onlyActive boolean
     * @return Depot[]
     */
    public function getDepots($onlyActive)
    {
        if($onlyActive)
            return $this->depotRepo->findBy(array( 'active' => true ));
        else
            return $this->depotRepo->findAll();
    }

    /**
     * Get depot by id
     * @param $depotId int
     * @return Depot
     */
    public function getDepotById($depotId)
    {
        $oneDepot = $this->depotRepo->find($depotId);
        if (!$oneDepot){
            throw new NotFoundHttpException("Depot not found with ID {$depotId}");
        }
        return $oneDepot;
    }

    /**
     * Returning depot edit form
     * @param $oneDepot Depot
     * @return FormInterface
     */
    public function getDepotForm($oneDepot)
    {
        $form = $this->formFactory->createBuilder(FormType::class, $oneDepot);

        $form->add("depot_code", TextType::class, array(
             'attr' => array( 'class' => 'form-control' )
            )
        );
        $form->add('depot_address', EntityType::class, array(
            'class' => 'AppBundle:Address',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->where('a.address_active = 1')
                    ->orderBy('a.address_city', 'ASC');
            },
            'choice_label' => function ($addr) {
                return $addr->getAddressPostalCode() . ' ' . $addr->getAddressCountry() . ' ' .
                $addr->getAddressCity() . ' ' . $addr->getAddressStreet1();
            },
            'attr' => array( 'class' => 'form-control' )
        ));
        $form->add("active", CheckboxType::class, array(
            'label' => 'Enable',
            'required' => false
        ));

        return $form->getForm();
    }

    /**
     * Add new depot
     * @param $oneDepot Depot
     */
    public function saveDepot($oneDepot)
    {
        $this->entityManager->persist($oneDepot);
        $this->entityManager->flush();
    }

    /**
     * Enable or disable depot
     * @param $depotId int
     * @param $enable boolean
     */
    public function enableDisableDepot($depotId, $enable)
    {
        $oneDepot = $this->getDepotById($depotId);
        $oneDepot->setActive($enable);
        $this->entityManager->flush();
    }

}