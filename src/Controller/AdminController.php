<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
	#[Route("/admin/category/new", name: "admin_create_category")]
	public function createCategory(Request $request): Response
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

		$user = $this->getUser();

		dump($user);
		
		return $this->render("home/index.html.twig", [
			"user" => $user
		]);
	}
}