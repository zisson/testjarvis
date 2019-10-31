<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FrontClientUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /** @var FrontClientUserService $frontClientUserService */
    private $frontClientUserService;

    public function __construct(FrontClientUserService $frontClientUserService)
    {
        $this->frontClientUserService = $frontClientUserService;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function index(): Response
    {
        $baseUrl = $this->getParameter('base_url');
        $response = $this->frontClientUserService->userGetList($baseUrl);
        $data = $response->getContent();

        return $this->render('user/index.html.twig', [
            'users' => json_decode($data, true),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $baseUrl = $this->getParameter('base_url');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->frontClientUserService->sendDataToCreate($user, $baseUrl);
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, FrontClientUserService $frontClientUserService): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $baseUrl = $this->getParameter('base_url');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->frontClientUserService->sendDataToUpdate($user, $baseUrl);
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
