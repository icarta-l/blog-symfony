<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	private $id;

	private $username;

	private $password;

	private $email;

	private $role = [];
}