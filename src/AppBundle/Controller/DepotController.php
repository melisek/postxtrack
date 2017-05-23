<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Depot;
use AppBundle\Service\IDepotService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepotController extends Controller
{
    /**
     * @var IDepotService
     */
    private $depotService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->depotService=$this->get("app.depotservice");
}

    /**
     * @Route("/depots", name="depot")
     * @Route("/depots/list", name="depotlist")
     */
    public function getList(){
        $arr = $this->depotService->getDepots(false);
        $response = $this->render('depots/depotlist.html.twig', array('depotlist'=>$arr));
        return $response;
    }

    /**
     * @Route("/depots/edit/{depotId}", name="depotedit")
     */
    public function getForm($depotId=0, Request $request)
    {
        if ($depotId) {
            $oneDepot = $this->depotService->getDepotById($depotId);
        } else {
            $oneDepot = new Depot();
        }

        $formInterface = $this->depotService->getDepotForm($oneDepot);

        $formInterface->handleRequest($request);

        if ($formInterface->isSubmitted() && $formInterface->isValid()) {
            $this->depotService->saveDepot($oneDepot);
            $this->addFlash('notice', 'Depot saved.');
            return $this->redirectToRoute("depotlist");
        }

        return $this->render('depots/depotform.html.twig', array("form" => $formInterface->createView()));
    }

    /**
     * @Route("/depots/enable/{depotId}", name="depotenable")
     */
    public function enable($depotId) {
        $this->depotService->enableDisableDepot($depotId, true);
        $this->addFlash('notice', 'Depot enabled.');
        return $this->redirectToRoute("depotlist");
    }

     /**
     * @Route("/depots/disable/{depotId}", name="depotdel")
     */
    public function delete($depotId) {
        $this->depotService->enableDisableDepot($depotId, false);
        $this->addFlash('notice', 'Depot disabled.');
        return $this->redirectToRoute("depotlist");
    }
}