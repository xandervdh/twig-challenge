<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LearningController extends AbstractController
{
    private string $name;
    private SessionInterface $session;

    /**
     * LearningController constructor.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    /**
     * @Route("/learning", name="learning")
     */
    public function index(): Response
    {
        return $this->render('learning/index.html.twig', [
            'controller_name' => 'LearningController',
        ]);
    }

    /**
     * @Route("/about-Becode", name="aboutMe")
     */
    public function aboutMe(): Response
    {
        $this->name = $this->session->get('name', 'Unknown');
        if ($this->name == 'Unknown'){
            return $this->redirectToRoute('showname');
            //$response = $this->forward('challenge-symfony-mvc/Controller/LearningController::showMyName', ['name' => $this->name]);
            //return $response;
        }
        return $this->render('learning/aboutMe.html.twig', [
            'name' => $this->name,
        ]);
    }

    /**
     * @Route("/", name="showname")
     */
    public function showMyName(): response
    {
        $this->name = $this->session->get('name', 'Unknown');
        $form = $this->createFormBuilder(null, [
            'action' => '/change-name',
            'method' => 'POST',
        ])
            ->add('name', TextType::class)
            ->getForm();
        return $this->render('learning/showName.html.twig', [
            'name' => $this->name,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change-name", name="changename",  methods={"POST"})
     */
    public function changeMyName()
    {
        $this->session->set('name', $_POST['form']['name']);
        return $this->forward(LearningController::class . '::showMyName');
    }

}
