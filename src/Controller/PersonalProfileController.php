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

class PersonalProfileController extends AbstractController
{
	/**
	 * Edit existing user profile
	 */
	#[Route("personal-profile/edit", name: "edit_profile")]
	public function editProfile(Request $request, ManagerRegistry $doctrine): Response|RedirectResponse
	{
		$this->denyAccessUnlessGranted(Role::USER->value);

		$user = $this->getUser();

		$form = $this->createFormBuilder($user)
		->add("username", TextType::class)
		->add("save", SubmitType::class, ["label" => "Edit Profile"])
		->getForm();

		return $this->renderForm("personal-profile/edit-infos.html.twig", [
			"form" => $form,
			"user" => $user
		]);
	}
}