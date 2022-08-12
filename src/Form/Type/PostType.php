<?php

namespace App\Form\Type;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\Type\CategoryType;

class PostType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder->add("title", TextType::class)
		->add("summary", TextareaType::class)
		->add("categories", EntityType::class, [
			"class" => Category::class,
			"choice_label" => "title",
			"multiple" => true,
			"expanded" => true

		])
		->add("content", TextareaType::class)
		->add("save", SubmitType::class, ["label" => "Publish"]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			"data_class" => Post::class
		]);
	}
}