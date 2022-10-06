<?php

namespace App\Tests\Tools;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait PostWriter 
{

	private string $title = "Test";
	private string $summary = "A fake summary to publish the post";
	private array $categories = [1];
	private string $content = "Here is my fake new content to test the post publishing logic";
	private array $fields = ["title", "summary", "categories", "content"];

	private function fillFormWithFilter(Form $form, string $blacklisted_field): Form
	{
		if (in_array($blacklisted_field, $this->fields)) {
			$key = array_search($blacklisted_field, $this->fields);
			array_splice($this->fields, $key, 1);
		}
		return $this->fillForm($form, "post");
	}

	private function getPostCreationForm(): Form
	{
		return $this->getForm($this->postCreationRouteName, "Publish");
	}

	private function fillPostFormWithoutCategories(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "categories");
	}

	private function fillPostFormWithoutSummary(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "summary");
	}

	private function fillPostFormWithoutContent(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "content");
	}

	private function fillPostFormWithoutTitle(Form $form): void
	{
		$form = $this->fillFormWithFilter($form, "title");
	}

	private function fillPostFormWithValidData(Form $form): void
	{
		$form = $this->fillForm($form, "post");
	}
}