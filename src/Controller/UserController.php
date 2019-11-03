<?php

namespace App\Controller;

use App\Dictionnary\FlashDictionary;
use App\Entity\User;
use App\Service\CreateUserService;
use App\Service\DeleteUserService;
use App\Service\ListUserService;
use App\Service\ShowUserItemService;
use App\Service\UpdateUserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/user")
 */
class UserController extends UserFormFactoryController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param ListUserService $listUserService
     * @return Response
     */
    public function index(ListUserService $listUserService): Response
    {
        $user = new User();
        $baseUrl = $this->getParameter('base_url');
        $response = $listUserService->sendUserData($baseUrl, $user);

        if ($response) {
            return $this->render('user/index.html.twig', [
                'users' => json_decode($response, true),
            ]);
        }
        return $this->redirectToRoute('error_exception');
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param CreateUserService $createUserService
     * @return Response
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, CreateUserService $createUserService): Response
    {
        $user = new User();
        $form = $this->formDataCreate($user);
        $baseUrl = $this->getParameter('base_url');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $response = $createUserService->sendUserData($baseUrl, $user);
            if ($response === JsonResponse::HTTP_CREATED) {
                $this->addFlash('success', FlashDictionary::CREATE_USER_SUCCESS);
                return $this->redirectToRoute('user_index');
            }
            $this->addFlash('error', FlashDictionary::CREATE_USER_ERROR);
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param User $user
     * @param ShowUserItemService $showUserItemService
     * @return Response
     */
    public function show(User $user, ShowUserItemService $showUserItemService): Response
    {
        $baseUrl = $this->getParameter('base_url');
        $response = $showUserItemService->sendUserData($baseUrl, $user);
        if (!$response) {
            return $this->redirectToRoute('error_exception');
        }
        return $this->render('user/show.html.twig', [
            'user' => json_decode($response, true),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param User $user
     * @param UpdateUserService $updateUserService
     * @return Response
     */
    public function edit(Request $request, User $user, UpdateUserService $updateUserService): Response
    {
        $form = $this->formDataCreate($user);
        $baseUrl = $this->getParameter('base_url');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $response = $updateUserService->sendUserData($baseUrl, $user);
            } catch (TransportExceptionInterface $e) {
            } catch (Exception $e) {
            }
            if ($response === JsonResponse::HTTP_OK) {
                $this->addFlash('success', FlashDictionary::EDIT_SUCCESS);
                return $this->redirectToRoute('user_index');
            }
            $this->addFlash('error', FlashDictionary::EDIT_ERROR);
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param User $user
     * @param DeleteUserService $deleteUserService
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function delete(Request $request, User $user, DeleteUserService $deleteUserService): Response
    {
        $baseUrl = $this->getParameter('base_url');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $response = $deleteUserService->sendUserData($baseUrl, $user);

            if ($response === JsonResponse::HTTP_NO_CONTENT) {
                $this->addFlash('success', FlashDictionary::DELETE_SUCCESS);
            } else {
                $this->addFlash('error', FlashDictionary::DELETE_ERROR);
            }
        }
        return $this->redirectToRoute('user_index');
    }
}
