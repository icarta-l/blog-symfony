<?php

namespace App\Tool;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

trait DatabaseHandler
{
	public function registerEntity(ManagerRegistry $doctrine, Post|User|Category $entity): void
	{
		$entityManager = $doctrine->getManager();
		$entityManager->persist($entity);
		$entityManager->flush();
	}
}