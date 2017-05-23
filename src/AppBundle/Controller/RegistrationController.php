<?php
// src/AppBundle/Controller/RegistrationController.php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\IUserService;
use Symfony\Component\DependencyInjection\ContainerInterface;


class RegistrationController extends Controller
{
    /**
     * @var IUserService
     */
    private $userService;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->userService=$this->get("app.userservice");
    }

    /**
    * @Route("/register", name="user_registration")
    */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // email check
            $emailexists = $this->userService->getUserByEmail($user->getEmail());
            if($emailexists)
            {
                $this->addFlash('warning', 'This email is already registered.');
                return $this->render(
                    'registration/register.html.twig',
                    array('form' => $form->createView())
                );
            }

            // Encode the password
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPwhash($password);

            // save the User
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Registration successful. Please login.');

            return $this->redirectToRoute('login_form');
        }

        return $this->render(
            'registration/register.html.twig',
             array('form' => $form->createView())
        );
    }
}