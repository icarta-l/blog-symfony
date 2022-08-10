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
use Doctrine\Persistence\ManagerRegistry;

class PostType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options, ManagerRegistry $doctrine): void
	{
		$repository = $doctrine->getRepository(Category::class);
		$categories = $repository->findAll();

		$builder->add("title", TextType::class)
		->add("summary", TextareaType::class)
		->add("categories", ChoiceType::class, [
			"choices" => $categories,
			"choice_value" => "title"
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