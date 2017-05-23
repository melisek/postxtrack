<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Service\IAddressService;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends Controller
{
    /**
     * @var IAddressService
     */
    private $addressService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->addressService=$this->get("app.addressservice");
    }

    /**
     *
     * @Route("/addresses/edit/{addressId}", name="addressedit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getEditForm($addressId=0, Request $request)
    {
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getUserId();
        if ($addressId) {

            $oneAddress = $this->addressService->getAddressById($addressId);
            $this->addressService->checkAddressUser($oneAddress,$userId);

        } else {
            $oneAddress = new Address();
        }

        $formInterface = $this->addressService->getAddressForm($oneAddress);

        $formInterface->handleRequest($request);

        if ($formInterface->isSubmitted() && $formInterface->isValid()) {

            $this->addressService->saveAddress($oneAddress);
            $this->addressService->saveUserAddress($userId, $oneAddress->getAddressId());
            $this->addFlash('notice', 'Address saved.');

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('addresses/addressform.html.twig', array(
            "form" => $formInterface->createView(),
        ));
    }

    /**
     * @Route("/addresses/enable/{addressId}", name="addressenable")
     */
    public function enable($addressId) {
        $this->addressService->enableDisableAddress($addressId, true);
        $this->addFlash('notice', 'Address enabled.');
        return $this->redirectToRoute("dashboard");
    }

    /**
     * @Route("/addresses/disable/{addressId}", name="addressdisable")
     */
    public function disable($addressId) {
        $this->addressService->enableDisableAddress($addressId, false);
        $this->addFlash('notice', 'Address disabled.');
        return $this->redirectToRoute("dashboard");
    }


}