<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Tool\DatabaseHandler;

class AdminController extends AbstractController
{
	use DatabaseHandler;

	#[Route("/admin/category/new", name: "admin_create_category")]
	public function createCategory(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$user = $this->getUser();

		$form = $this->createForm(CategoryType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$category = $form->getData();
			$repository = $doctrine->getRepository(Category::class);

			if ($repository->findOneBy(["title" => $category->getTitle()]) === null) {
				$this->registerEntity($doctrine, $category);

				return $this->redirectToRoute("category_successfully_created");

			} else {
				$category_exists = true;
			}
		}
		
		return $this->renderForm("admin/create-category.html.twig", [
			"form" => $form,
			"category_exists" => (isset($category_exists) && $category_exists === true) ? $category_exists : false,
			"user" => $user
		]);
	}

	#[Route("admin/category/new/success", name: "category_successfully_created")]
	public function postSuccessfullyCreated(Request $request, ManagerRegistry $doctrine): Response
	{
		return $this->render("admin/create-category-success.html.twig");
	}
}