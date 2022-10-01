<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use App\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Tool\DatabaseHandler;

class BlogController extends AbstractController
{
	use DatabaseHandler;

	#[Route("/blog/post/new", name: "create_post")]
	public function createPost(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted('ROLE_USER');

		$user = $this->getUser();

		$form = $this->createForm(PostType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getPostDataAndRegisterInDatabase($form, $user, $doctrine);

			return $this->redirectToRoute("post_successfully_created");
		}

		return $this->renderForm("blog/create-post.html.twig", [
			"form" => $form,
			"user" => $user
		]);
	}

	private function getPostDataAndRegisterInDatabase($form, User $user, ManagerRegistry $doctrine): void
	{
		$post = $form->getData();
		$this->setRemainingPostProperties($post, $user);
		$this->registerEntity($doctrine, $post);
	}

	private function setRemainingPostProperties(Post $post, User $user): void
	{
		$post->setPublishedAt((new \DateTimeImmutable("now", new \DateTimeZone("Europe/Rome"))));
		$post->setAuthor($user);
		$slug = \strtolower(\str_replace(" ", "-", $post->getTitle()));
		$post->setSlug($slug);
	}

	#[Route("blog/post/new/success", name: "post_successfully_created")]
	public function postSuccessfullyCreated(Request $request, ManagerRegistry $doctrine): Response
	{
		return $this->render("blog/create-post-success.html.twig");
	}

	#[Route("/blog", name: "print_all_posts")]
	public function list(Request $request, ManagerRegistry $doctrine): Response
	{
		$repository = $doctrine->getRepository(Post::class);
		$posts = $repository->findAll();

		return $this->render("blog/index.html.twig", [
			"posts" => $posts
		]);
	}

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