<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllCategoriesWithAtLeastOnePost(int $category_id = 0): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT category
            FROM App\Entity\Category category
            JOIN category.posts post
            WHERE category.id != :category_id
            GROUP BY category.id"
        )->setParameter("category_id", $category_id);

        return $query->getResult();
    }
}