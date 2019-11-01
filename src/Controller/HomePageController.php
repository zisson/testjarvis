<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Debug\Exception\FlattenException;
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
     * @param FlattenException $exception
     * @return Response
     */
    public function exception(FlattenException $exception): Response
    {
        return $this->render('error_exception.html.twig', [
            'message' => $exception->getMessage()
        ]);
    }
}
