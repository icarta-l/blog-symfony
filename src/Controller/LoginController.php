<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
	/**
	 * @Route("/register", name="register")
	 */
	public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): Response
	{
		$form = $this->createForm(UserType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $form->getData();
			$hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
			$user->setPassword($hashedPassword);
			dump($user);

			$repository = $doctrine->getRepository(User::class);

			if ($repository->findOneBy(["username" => $user->getUsername()]) === null) {
				$entityManager = $doctrine->getManager();
				$entityManager->persist($user);
				$entityManager->flush();

			} else {
				$user_exists = true;
			}
		}

		return $this->renderForm("login/register.html.twig", [
			"form" => $form,
			"user_exists" => (isset($user_exists) && $user_exists === true) ? $user_exists : false
		]);
	}

	/**
	 * @Route("/login", name="login") 
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render("login/index.html.twig", [
			"last_username" => $lastUsername,
			"error" => $error
		]);
	}
}