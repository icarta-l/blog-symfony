<?php

namespace App\Tests\Tools;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait PostWriter 
{
	private string $title = "Test";
	private string $summary = "A fake summary to publish the post";
	private array $categories = [58];
	private string $content = "Here is my fake new content to test the post publishing logic";
	private array $fields = ["title", "summary", "categories", "content"];


	private function getPostCreationForm(): Form
	{
		return $this->getForm($this->postCreationRouteName, "Publish");
	}

	private function fillPostFormWithoutCategories(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "categories", "post");
	}

	private function fillPostFormWithoutSummary(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "summary", "post");
	}

	private function fillPostFormWithoutContent(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "content", "post");
	}

	private function fillPostFormWithoutTitle(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "title", "post");
	}

	private function fillPostFormWithValidData(Form $form): void
	{
		$form = $this->fillForm($form, "post");
	}
}