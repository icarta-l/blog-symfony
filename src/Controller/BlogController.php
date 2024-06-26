<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Tool\DatabaseHandler;
use App\Tool\FormHandler;
use App\Roles\Role;

class BlogController extends AbstractController
{
	use DatabaseHandler, FormHandler;

	/**
	 * Create a new post
	 */
	#[Route("/blog/post/new", name: "create_post")]
	public function createPost(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted(Role::USER->value);
		$this->doctrine = $doctrine;

		return $this->handlePostCreationResponse($request);
	}

	/**
	 * Handle post creation response
	 */
	private function handlePostCreationResponse(Request $request): Response|RedirectResponse
	{
		if ($this->isFormValidAndSubmitted($request)) {
			$this->getPostDataAndRegisterInDatabase();

			return $this->redirectToRoute("post_successfully_created");
		} else {
			return $this->renderForm("blog/create-post.html.twig", [
				"form" => $this->form,
				"user" => $this->getUser()
			]);
		}
	}

	/**
	 * Get post data and register in database
	 */
	private function getPostDataAndRegisterInDatabase(): void
	{
		$post = $this->form->getData();
		$post->setRemainingProperties($this->getUser());
		$this->registerEntity($post);
	}

	/**
	 * Render success page
	 */
	#[Route("/blog/post/new/success", name: "post_successfully_created")]
	public function postSuccessfullyCreated(Request $request, ManagerRegistry $doctrine): Response
	{
		return $this->render("blog/create-post-success.html.twig", ["user" => $this->getUser()]);
	}

	/**
	 * List all posts
	 */
	#[Route("/blog/{slug}", name: "print_all_posts")]
	public function list(Request $request, ManagerRegistry $doctrine, string $slug = "all"): Response
	{
		return $this->render("blog/index.html.twig", [
			"posts" => $this->getPostsOnListPage($doctrine, $slug),
			"categories" => $doctrine->getRepository(Category::class)->findAllCategoriesWithAtLeastOnePost(),
			"user" => $this->getUser()
		]);
	}

	/**
	 * Get posts on list page
	 */
	private function getPostsOnListPage(ManagerRegistry $doctrine, string $slug): array
	{
		if ($slug === "all") {
			return $doctrine->getRepository(Post::class)->findAll();
		} else {
			return $doctrine->getRepository(Post::Class)->findAllPostsByCategory($slug);
		}
	}

	/**
	 * Show a single post
	 */
	#[Route("/blog/post/{slug}", name: "print_post")]
	public function show(Request $request, ManagerRegistry $doctrine, string $slug): Response
	{
		return $this->render("blog/single-post.html.twig", [
			"post" => $doctrine->getRepository(Post::class)->findOneBy(["slug" => $slug]),
			"user" => $this->getUser()
		]);
	}
}