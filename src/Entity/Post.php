<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
class Post
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private int $id;

	#[Assert\NotBlank]
	#[ORM\Column]
	private string $title;

	#[Assert\NotBlank]
	#[ORM\Column]
	private string $content;

	#[ORM\Column]
	private string $slug;

	#[ORM\ManyToOne(targetEntity: User::class, inversedBy: "posts")]
	private $author;

	#[Assert\NotBlank]
	#[ORM\Column]
	private string $summary;

	#[Assert\Count(
	min: 1,
	minMessage: "You must choose at least one category"
	)]
	#[ORM\ManyToMany(targetEntity: Category::class, inversedBy: "posts")]
	private $categories;

	#[ORM\Column(type: "datetime")]
	private $publishedAt;

	public function __construct()
	{
		$this->categories = new ArrayCollection();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;

		return $this;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): self
	{
		$this->slug = $slug;

		return $this;
	}

	public function getAuthor(): User
	{
		return $this->author;
	}

	public function setAuthor(User $author): self
	{
		$this->author = $author;

		return $this;
	}

	public function getSummary(): string
	{
		return $this->summary;
	}

	public function setSummary(string $summary): self
	{
		$this->summary = $summary;

		return $this;
	}

	/**
	 * @return Collection|Category[]
	 */
	public function getCategories(): Collection
	{
		return $this->categories;
	}

	public function addCategory(Category $category): void
	{
		$category->addPost($this);

		if (!$this->categories->contains($category)) {
			$this->categories->add($category);
		}
	}

	public function removeCategory(Category $category): void
	{
		$category->removePost($this);

		if ($this->categories->contains($category)) {
			$this->categories->removeElement($category);	
		}
	}

	public function getPublishedAt(): string
	{
		return $this->publishedAt->format("Y-m-d H:i:s");
	}

	public function setPublishedAt(\DateTimeInterface $datetime): self
	{
		$this->publishedAt = $datetime;

		return $this;
	}
}