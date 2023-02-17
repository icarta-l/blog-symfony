<?php

namespace App\Tool;

use App\Entity\EntityInterface;
use Doctrine\Persistence\ManagerRegistry;

trait DatabaseHandler
{
	private ManagerRegistry $doctrine;

	/**
	 * Register an entity in the database
	 */
	public function registerEntity(EntityInterface $entity): void
	{
		$entityManager = $this->doctrine->getManager();
		$entityManager->persist($entity);
		$entityManager->flush();
	}
}