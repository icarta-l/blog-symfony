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
use App\Tool\FormHandler;
use App\Roles\Role;

class AdminController extends AbstractController
{
	use DatabaseHandler, FormHandler;

	/**
	 * Create a new category
	 */
	#[Route("/admin/category/new", name: "admin_create_category")]
	public function createCategory(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted(Role::ADMIN->value, null, "User tried to access a page without having " . Role::ADMIN->value);

		$this->doctrine = $doctrine;

		return $this->handleCategoryCreationResponse($request);
	}

	/**
	 * Handle category creation response
	 */
	private function handleCategoryCreationResponse(Request $request): Response|RedirectResponse
	{
		if ($this->isFormValidAndSubmitted($request, CategoryType::class) && ($category_is_new = $this->categoryIsNew())) {
			$this->generateCategorySlug(($category = $this->form->getData()));
			$this->registerEntity($category);

			return $this->redirectToRoute("category_successfully_created");
		} else {

			return $this->renderForm("admin/create-category.html.twig", [
				"form" => $this->form,
				"category_exists" => (isset($category_is_new) && $category_is_new) ? false : ((!isset($category_is_new)) ? false : true),
				"user" => $this->getUser()
			]);
		}
	}

	/**
	 * Generate appropriate slug for category
	 */
	private function generateCategorySlug(Category $category): void
	{
		$slug = $category->generateSlug();
		$appendedNumber = 1;
		while ($this->doctrine->getRepository(Category::class)->findOneBy(["slug" => $slug]) !== null) {
			$slug .= $appendedNumber;
			$appendedNumber++;
		}
		$category->setSlug($slug);
	}

	/**
	 * Check category doesn't already exist 
	 */
	private function categoryIsNew(): bool
	{
		return $this->doctrine->getRepository(Category::class)->findOneBy(["title" => $this->form->getData()->getTitle()]) === null;
	}

	/**
	 * Render category creation success page
	 */
	#[Route("admin/category/new/success", name: "category_successfully_created")]
	public function postSuccessfullyCreated(Request $request, ManagerRegistry $doctrine): Response
	{
		return $this->render("admin/create-category-success.html.twig", ["user" => $this->getUser()]);
	}
}