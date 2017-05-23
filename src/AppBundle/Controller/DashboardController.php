<?php
namespace AppBundle\Controller;

use AppBundle\Service\IParcelService;
use AppBundle\Service\IUserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
    /**
     * @var IParcelService
     */
    private $parcelService;

    /**
     * @var IUserService
     */
    private $userService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->parcelService=$this->get("app.parcelservice");
        $this->userService=$this->get("app.userservice");
}

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function getList(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $useraddresses = $this->userService->getUserAddressesById($user->getUserId());
        $sentParcels = $this->parcelService->getParcelByUserId($user->getUserId(), true);
        $recParcels = $this->parcelService->getParcelByUserId($user->getUserId(), false);

        $response = $this->render('dashboard/dashboardlist.html.twig',
            array(
                'sentparcels'=> $sentParcels,
                'recparcels'=> $recParcels,
                'user' => $user,
                'uaddrs' => $useraddresses
                )
        );
        return $response;
    }

}