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

class BlogController extends AbstractController
{
	use DatabaseHandler, FormHandler;

	/**
	 * Create a new post
	 */
	#[Route("/blog/post/new", name: "create_post")]
	public function createPost(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted('ROLE_USER');
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
			"posts" => $doctrine->getRepository(Post::class)->findAll(),
			"categories" => $doctrine->getRepository(Category::class)->findAllCategoriesWithAtLeastOnePost(),
			"user" => $this->getUser()
		]);
	}

	/**
	 * Show a single post
	 */
	#[Route("/blog/post/{slug}", name: "print_post")]
	public function show(Request $request, ManagerRegistry $doctrine, string $slug): Response
	{
		$repository = $doctrine->getRepository(Post::class);
		$post = $repository->findOneBy(["slug" => $slug]);

		return $this->render("blog/single-post.html.twig", [
			"post" => $post
		]);
	}
}