<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPostsByCategory(string $slug): array
    {
    	$entityManager = $this->getEntityManager();

    	$query = $entityManager->createQuery(
    		"SELECT posts
    		FROM App\Entity\Post posts
    		JOIN posts.categories category
    		WHERE category.slug = :slug"
    	)->setParameter("slug", $slug);

    	return $query->getResult();
    }
}