<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
#[ORM\Table(name: "users")]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private int $id;

	#[Assert\NotBlank]
	#[ORM\Column(unique: true)]
	private string $username;

	#[Assert\NotBlank]
	#[ORM\Column]
	private string $password;

	#[Assert\NotBlank]
	#[ORM\Column(unique: true)]
	private string $email;

	#[ORM\Column(type: "json")]
	private $roles = [];

	#[ORM\OneToMany(targetEntity: Post::class, mappedBy: "author")]
	private $posts;

	public function __construct()
	{
		$this->posts = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getEmail(): string
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

	/**
	 * @return Collection|Post[]
	 */
	public function getPosts(): Collection
	{
		return $this->posts;
	}

	public function addPost(Post $post): void
	{
		$this->posts->add($post);
	}

	public function removePost(Post $post): void
	{
		$this->posts->removeElement($post);
	}
}