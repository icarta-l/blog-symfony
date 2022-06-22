<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add("username", TextType::class)
		->add("password", TextType::class)
		->add("email", TextType::class)
		->add("roles", ChoiceType::class, [
			"choices" => [
				"User" => "ROLE_USER",
				"Admin" => "ROLE_ADMIN"
			],
			"expanded" => true,
			"multiple" => false
		])
		->add("save", SubmitType::class, ["label" => "Register User"]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => User::class
		]);
	}
}