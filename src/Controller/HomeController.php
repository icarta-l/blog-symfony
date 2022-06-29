<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 */
	public function index(): Response
	{
		$user = $this->getUser();
		
		return $this->render("home/index.html.twig", [
			"user" => $user
		]);
	}
}