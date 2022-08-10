<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class AdminController extends AbstractController
{
	#[Route("/admin/category/new", name: "admin_create_category")]
	public function createCategory(Request $request, ManagerRegistry $doctrine): Response
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$form = $this->createForm(CategoryType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$category = $form->getData();
			$repository = $doctrine->getRepository(Category::class);

			if ($repository->findOneBy(["title" => $category->getTitle()]) === null) {
				$entityManager = $doctrine->getManager();
				$entityManager->persist($category);
				$entityManager->flush();
			} else {
				$category_exists = true;
			}
		}
		
		return $this->renderForm("admin/create-category.html.twig", [
			"form" => $form,
			"category_exists" => (isset($category_exists) && $category_exists === true) ? $category_exists : false
		]);
	}
}