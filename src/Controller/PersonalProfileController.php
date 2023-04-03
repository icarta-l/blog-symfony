<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Persistence\ManagerRegistry;
use App\Roles\Role;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Form;

class PersonalProfileController extends AbstractController
{
	/**
	 * Edit existing user profile
	 */
	#[Route("personal-profile/edit", name: "edit_profile")]
	public function editProfile(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted(Role::USER->value);

		$this->editUserIfFormIsCorrectlyFilled($form = $this->makeCustomUserFormForEditing($request, ( $user = $this->getUser() )), $doctrine);

		return $this->renderForm("personal-profile/edit-infos.html.twig", [
			"form" => $form,
			"user" => $user
		]);
	}

	private function editUserIfFormIsCorrectlyFilled(Form $form, ManagerRegistry $doctrine): void
	{
		if ($form->isSubmitted() && $form->isValid()) {
			$doctrine->getManager()->flush();
		}
	}

	private function makeCustomUserFormForEditing(Request $request, User $user): Form
	{
		return $this->createFormBuilder($user)
		->add("username", TextType::class, [
			"required" => true,
			"constraints" => [new NotBlank()]
		])
		->add("save", SubmitType::class, ["label" => "Edit Profile"])
		->getForm()->handleRequest($request);
	}
}