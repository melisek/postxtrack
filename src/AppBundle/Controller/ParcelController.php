<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Parcel;
use AppBundle\Service\IParcelService;
use AppBundle\Service\IStatusService;
use AppBundle\Service\IDepotService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParcelController extends Controller
{
    /**
     * @var IParcelService
     */
    private $parcelService;

    /**
     * @var IStatusService
     */
    private $statusService;

    /**
     * @var IDepotService
     */
    private $depotService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->parcelService=$this->get("app.parcelservice");
        $this->statusService=$this->get("app.statusservice");
        $this->depotService=$this->get("app.depotservice");
    }

    /**
     * @Route("/parcels", name="parcel")
     * @Route("/parcels/list", name="parcellist")
     */
    public function getList(){
        $arr = $this->parcelService->getParcels();
        $response = $this->render('parcels/parcellist.html.twig', array('parcellist'=>$arr));
        return $response;
    }

    /**
     * @Route("/parcels/track", name="parceltrack")
     * @Route("/parcels/track/{parcel_code}", name="parceltrackcode")
     */
    public function trackAction($parcel_code = null, Request $request)
    {
        $oneParcel = new Parcel(1);

        $form = $this->createFormBuilder($oneParcel)
            ->add('parcel_code', TextType::class, array(
                    'label' => false,
                    'data' => $parcel_code,
                    'attr' => array(
                        'class' => 'input-lg',
                        'placeholder' => 'Tracking code'))
            )
            ->add('save', SubmitType::class, array(
                'label' => 'Track',
                'attr' => array(
                       'class' => 'btn btn-success btn-lg')
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($parcel_code != null || $form->isSubmitted() && $form->isValid()) {

            if(!$parcel_code)
                $parcel_code = $form->getData()->getParcelCode();

            $oneParcel = $this->parcelService->getParcelByCode($parcel_code);

            $statuses = array();

            if(!$oneParcel)
            {
                $this->addFlash('warning', 'No parcel found with this tracking code.');
            }
            else {
                $this->addFlash('notice', 'Parcel found.');
                $statuses = $this->parcelService->getStatusesByParcel($oneParcel[0]);
            }

            return $this->render(
                'parcels/parceldata.html.twig',
                array(
                    'form' => $form->createView(),
                    'parcels' => $oneParcel,
                    'statuses' => $statuses)
            );
        }

        return $this->render(
            'parcels/parceltrack.html.twig', array(
                'form' => $form->createView()
            )
        );

    }


    /**
     * @Route("/parcels/edit/{parcelId}", name="parceledit")
     */
    public function getEditForm($parcelId=0, Request $request)
    {
        if ($parcelId) {
            $oneParcel = $this->parcelService->getParcelById($parcelId);
        } else {
            $this->addFlash('warning', 'Parcel not found.');
            return $this->redirectToRoute("parcellist");
        }

        $formInterface = $this->parcelService->getParcelEditForm($oneParcel);

        $formInterface->handleRequest($request);

        if ($formInterface->isSubmitted() && $formInterface->isValid()) {

            $this->parcelService->saveParcelStatus($oneParcel, $request->get('status'), $request->get('depot'));
            $this->addFlash('notice', 'Parcel status saved.');

            return $this->redirectToRoute("parcellist");
        }

        return $this->render('parcels/parceledit.html.twig', array(
            "form" => $formInterface->createView(),
            "parcelstatuses" => $this->parcelService->getStatusesByParcel($parcelId),
            "statuses" => $this->statusService->getStatuses(),
            "depots" => $this->depotService->getDepots(true)
        ));
    }


    /**
     * @Route("/parcels/post/{parcelId}", name="parcelpost")
     */
    public function getForm($parcelId=0, Request $request)
    {
        if ($parcelId) {
            $oneParcel = $this->parcelService->getParcelById($parcelId);
        } else {
            $maxId = $this->parcelService->getMaxParcelId();
            $oneParcel = new Parcel($maxId);
        }

        $userId = $this->get('security.token_storage')->getToken()->getUser()->getUserId();

        $formInterface = $this->parcelService->getParcelPostForm($oneParcel, $userId);

        $formInterface->handleRequest($request);

        if ($formInterface->isSubmitted() && $formInterface->isValid()) {

            if($oneParcel->getSenderAddress() == $oneParcel->getRecAddress()) {
                $this->addFlash('warning', 'Sender and recipient addresses cannot be equal.');
            }
            else {

                $this->parcelService->saveParcel($oneParcel);
                $this->addFlash('notice', 'Parcel saved. Tracking code: ' . $oneParcel->getParcelCode());
                return $this->redirectToRoute("dashboard");
            }
        }

        return $this->render('parcels/parcelpost.html.twig', array("form" => $formInterface->createView()));
    }

}