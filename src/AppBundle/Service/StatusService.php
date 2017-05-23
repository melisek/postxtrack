<?php
/**
 * Created by PhpStorm.
 * User: hallgato
 * Date: 2016.11.28.
 * Time: 13:58
 */

namespace AppBundle\Service;


use AppBundle\Entity\Status;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatusService implements IStatusService
{
    private $entityManager;
    private $formFactory;

    private $statusRepo;

    /**
     * StatusService constructor.
     * @param $entityManager EntityManager
     * @param $formFactory FormFactory
     */
    public function __construct($entityManager, $formFactory)
    {
        $this->entityManager=$entityManager;
        $this->formFactory=$formFactory;

        $this->statusRepo=$entityManager->getRepository(Status::class);
    }

    /**
     * @return Status[]
     */
    public function getStatuses()
    {
        return $this->statusRepo->findAll();
    }

    /**
     * @param $statusId int
     * @return Status
     */
    public function getStatusById($statusId)
    {
        $oneStatus = $this->statusRepo->find($statusId);
        if (!$oneStatus){
            throw new NotFoundHttpException("Status not found with ID {$statusId}");
        }
        return $oneStatus;
    }

    /**
     * @param $oneStatus Status
     * @return FormInterface
     */
    public function getStatusForm($oneStatus)
    {
        $form = $this->formFactory->createBuilder(FormType::class, $oneStatus);

        $form->add("status_name", TextType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => 'Name'
            )
        );
        $form->add("status_desc", TextareaType::class, array(
                'attr' => array( 'class' => 'form-control' ),
                'label' => "Description"
            )
        );

        return $form->getForm();
    }

    /**
     * @param $oneStatus Status
     */
    public function saveStatus($oneStatus)
    {
        $this->entityManager->persist($oneStatus);
        $this->entityManager->flush();
    }

    /**
     * @param $statusId int
     */
    public function deleteStatus($statusId)
    {
        $oneStatus = $this->getStatusById($statusId);
        $this->entityManager->remove($oneStatus);
        $this->entityManager->flush();
    }


}