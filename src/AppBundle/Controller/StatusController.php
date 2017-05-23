<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Status;
use AppBundle\Service\IStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends Controller
{
    /**
     * @var IStatusService
     */
    private $statusService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->statusService=$this->get("app.statusservice");
}

    /**
     * @Route("/statuses", name="status")
     * @Route("/statuses/list", name="statuslist")
     */
    public function getList(){
        $arr = $this->statusService->getStatuses();
        $response = $this->render('statuses/statuslist.html.twig', array('statuslist'=>$arr));
        return $response;
    }

    /**
     * @Route("/statuses/edit/{statusId}", name="statusedit")
     */
    public function getForm($statusId=0, Request $request)
    {
        if ($statusId) {
            $oneStatus = $this->statusService->getStatusById($statusId);
        } else {
            $oneStatus = new Status();
        }

        $formInterface = $this->statusService->getStatusForm($oneStatus);

        $formInterface->handleRequest($request);

        if ($formInterface->isSubmitted() && $formInterface->isValid()) {
            $this->statusService->saveStatus($oneStatus);
            $this->addFlash('notice', 'Status saved.');
            return $this->redirectToRoute("statuslist");
        }

        return $this->render('statuses/statusform.html.twig', array("form" => $formInterface->createView()));
    }

     /**
     * @Route("/statuses/delete/{statusId}", name="statusdel")
     */
    public function delete($statusId) {
        $this->statusService->deleteStatus($statusId);
        $this->addFlash('notice', 'Status deleted.');
        return $this->redirectToRoute("statuslist");
    }
}