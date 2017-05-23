<?php

namespace AppBundle\Service;

use AppBundle\Entity\Status;
use Symfony\Component\Form\FormInterface;

interface IStatusService {

    /**
     * @return Status[]
     */
    public function getStatuses();

    /**
     * @param $statusId int
     * @return Status
     */
    public function getStatusById($statusId);

    /**
     * @param $oneStatus Status
     * @return FormInterface
     */
    public function getStatusForm($oneStatus);

    /**
     * @param $oneStatus Status
     */
    public function saveStatus($oneStatus);

    /**
     * @param $statusId int
     */
    public function deleteStatus($statusId);
}