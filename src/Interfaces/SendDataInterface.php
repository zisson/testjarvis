<?php


namespace App\Interfaces;


use App\Entity\User;

interface SendDataInterface
{
    public function sendUserData(string $baseUrl, User $user);
}