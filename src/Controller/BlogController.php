<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;

class BlogController extends AbstractController
{
	#[Route("/blog/post/new", name: "create_post")]
	public function createPost(Request $request, ManagerRegistry $doctrine): Response
	{
		$this->denyAccessUnlessGranted('ROLE_USER');

		$user = $this->getUser();

		$form = $this->createForm(PostType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$post = $form->getData();

			$post->setPublishedAt((new \DateTimeImmutable("now", new \DateTimeZone("Europe/Rome"))));
			$post->setAuthor($user);
			$slug = \strtolower(\str_replace(" ", "-", $post->getTitle()));
			$post->setSlug($slug);

			$entityManager = $doctrine->getManager();
			$entityManager->persist($post);
			$entityManager->flush();
		}

		return $this->renderForm("blog/create-post.html.twig", [
			"form" => $form,
			"user" => $user
		]);
	}

	#[Route("/blog", name: "print_all_posts")]
	public function list(Request $request, ManagerRegistry $doctrine): Response
	{
		$repository = $doctrine->getRepository(Post::class);
		$posts = $repository->findAll();

		dump($posts);

		return $this->render("blog/index.html.twig", [
			"posts" => $posts
		]);
	}

	#[Route("/blog/post/{slug}", name: "print_post")]
	public function show(Request $request, ManagerRegistry $doctrine, string $slug): Response
	{
		$repository = $doctrine->getRepository(Post::class);
		$post = $repository->findOneBy(["slug" => $slug]);

		dump($post);
		dump($post->getCategories()->getValues());
		dump($post->getAuthor());

		return $this->render("blog/single-post.html.twig", [
			"post" => $post
		]);
	}
}