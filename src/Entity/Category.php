<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity()]
class Category
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private int $id;

	#[ORM\Column]
	private string $title;

	#[ORM\ManyToMany(targetEntity: Post::class, mappedBy: "categories")]
	private $posts;

	#[ORM\Column]
	private string $description;

	public function __construct()
	{
		$this->posts = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title)
	{
		$this->title = $title;

		return $this;
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
		if (!$this->posts->contains($post)) {
			$this->posts->add($post);
		}
	}

	public function removePost(Post $post): void
	{
		if ($this->posts->contains($post)) {
			$this->posts->removeElement($post);
		}
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}
}