<?php

namespace AppBundle\Service;

use AppBundle\Entity\Depot;
use Symfony\Component\Form\FormInterface;

interface IDepotService {

    /**
     * @param $onlyActive boolean
     * @return Depot[]
     */
    public function getDepots($onlyActive);

    /**
     * @param $depotId int
     * @return Depot
     */
    public function getDepotById($depotId);

    /**
     * @param $oneDepot Depot
     * @return FormInterface
     */
    public function getDepotForm($oneDepot);

    /**
     * @param $oneDepot Depot
     */
    public function saveDepot($oneDepot);

    /**
     * @param $depotId int
     * @param $enable boolean
     */
    public function enableDisableDepot($depotId, $enable);

}