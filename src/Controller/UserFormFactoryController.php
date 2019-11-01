<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;

class UserFormFactoryController extends AbstractController
{
    /**
     * @param User $user
     * @return FormInterface
     */
    public function formDataCreate(User $user): FormInterface
    {
        return $this->createForm(UserType::class, $user);
    }

}