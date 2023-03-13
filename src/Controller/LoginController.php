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
use App\Tool\FormHandler;


class LoginController extends AbstractController
{
	use DatabaseHandler, FormHandler;

	private UserPasswordHasherInterface $passwordHasher;

	/**
	 * Create a new user
	 */
	#[Route("/register", name: "register")]
	public function register(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->doctrine = $doctrine;
		$this->passwordHasher = $passwordHasher;

		return $this->handleUserCreationResponse($request);
	}

	/**
	 * Handle user creation response
	 */
	private function handleUserCreationResponse(Request $request): Response|RedirectResponse
	{
		if ($this->isFormValidAndSubmitted($request, UserType::class) && !($user_exists = $this->handleUserFormData())) {
			return $this->redirectToRoute("user_successfully_created");
		} else {
			return $this->renderForm("login/register.html.twig", [
				"form" => $this->form,
				"user_exists" => (isset($user_exists) && $user_exists === true) ? $user_exists : false
			]);
		}
	}

	/**
	 * Get user form data and register in database if user does not exist
	 */
	private function handleUserFormData(): bool
	{
		if ( !($user_exists = $this->userExistsInDatabase( ($user = $this->form->getData()) )) ) {
			$this->registerNewUserInDatabase($user);

			return $user_exists;
		} else {
			return $user_exists;
		}
	}

	/**
	 * Check if user exists in database
	 */
	private function userExistsInDatabase(User $user): bool
	{
		return $this->doctrine->getRepository(User::class)->findOneBy(["username" => $user->getUsername()]) !== null;
	}

	/**
	 * Register new user in database with hashed password
	 */
	private function registerNewUserInDatabase(User $user): void
	{
		$user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));

		$this->registerEntity($user);
	}

	/**
	 * Render success page
	 */
	#[Route("/login/user/new/success", name: "user_successfully_created")]
	public function userSuccessfullyCreated(Request $request): Response
	{
		return $this->render("login/create-user-success.html.twig");
	}

	/**
	 * Login existing user 
	 */
	#[Route("/login", name: "login")]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		return $this->render("login/index.html.twig", [
			"last_username" => $authenticationUtils->getLastUsername(),
			"error" => $authenticationUtils->getLastAuthenticationError()
		]);
	}
}