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
	 * Render home page
	 */
	#[Route("/", name: "home")]
	public function index(): Response
	{		
		return $this->render("home/index.html.twig", [
			"user" => $this->getUser()
		]);
	}
}