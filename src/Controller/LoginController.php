<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 
 */
class LoginController extends AbstractController
{
	/**
	 * @Route("/register", name="register")
	 */
	public function register(Request $request): Response
	{
		$form = $this->createForm(UserType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			dump("Form data");
			dump($form->getData());
		}

		return $this->renderForm("login/register.html.twig", [
			"form" => $form
		]);
	}
}