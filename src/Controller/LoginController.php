<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\FormInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Tool\DatabaseHandler;


class LoginController extends AbstractController
{
	use DatabaseHandler;

	/**
	 * @Route("/register", name="register")
	 */
	public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$form = $this->createForm(UserType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user_exists = $this->handleUserFormData($form, $doctrine, $passwordHasher);
			if (!$user_exists) {
				return $this->redirectToRoute("user_successfully_created");
			}
		}

		return $this->renderForm("login/register.html.twig", [
			"form" => $form,
			"user_exists" => (isset($user_exists) && $user_exists === true) ? $user_exists : false
		]);
	}

	private function handleUserFormData(FormInterface $form, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): bool
	{
		$user = $form->getData();
		$repository = $doctrine->getRepository(User::class);

		if ($repository->findOneBy(["username" => $user->getUsername()]) === null) {
			$this->registerNewUserInDatabase($passwordHasher, $user, $doctrine);

			return false;
		} else {
			return true;
		}
	}

	private function registerNewUserInDatabase(UserPasswordHasherInterface $passwordHasher, User $user, ManagerRegistry $doctrine): void
	{
		$hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
		$user->setPassword($hashedPassword);

		$this->registerEntity($doctrine, $user);
	}

	#[Route("login/user/new/success", name: "user_successfully_created")]
	public function userSuccessfullyCreated(Request $request): Response
	{
		return $this->render("login/create-user-success.html.twig");
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