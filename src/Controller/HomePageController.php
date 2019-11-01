<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', []);
    }

    /**
     * @Route("/exception", name="error_exception", methods={"GET"})
     */
    public function exception(): Response
    {
        return $this->render('error_exception.html.twig', []);
    }
}
