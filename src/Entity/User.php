<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", unique=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getUserIdentifier(): string
	{
		return $this->email;
	}

	public function getRoles(): array
	{
		$roles = $this->roles;

		$roles[] = "ROLE_USER";

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self 
	{
		$this->password = $password;

		return $this;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	public function eraseCredentials()
	{
		
	}
}