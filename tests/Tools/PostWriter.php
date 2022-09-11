<?php

namespace App\Tests\Tools;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait PostWriter {
	private string $title = "Test";
	private string $summary = "A fake summary to publish the post";
	private array $categories = [1];
	private string $content = "Here is my fake new content to test the post publishing logic";
	private array $fields = ["title", "summary", "categories", "content"];

	private function fillForm(Form $form): Form
	{
		foreach ($this->fields as $field) {
			$field_name = "post[" . $field . "]";
			if (!$form->has($field_name)) {
				continue;
			}
			$form[$field_name] = $this->$field;
		}
		return $form;
	}

	private function fillFormWithFilter(Form $form, string $blacklisted_field): Form
	{
		if (in_array($blacklisted_field, $this->fields)) {
			$key = array_search($blacklisted_field, $this->fields);
			array_splice($this->fields, $key, 1);
		}
		return $this->fillForm($form);
	}

	private function getPostCreationForm(): Form
	{
		return $this->getForm($this->postCreationRouteName);
	}

	private function getForm(string $route_name): Form
	{
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route_name));
		return $crawler->selectButton("Publish")->form();
	}

	private function fillPostFormWithoutCategories($form): void
	{
		$form = $this->fillFormWithFilter($form, "categories");
	}

	private function fillPostFormWithoutSummary($form): void
	{
		$form = $this->fillFormWithFilter($form, "summary");
	}

	private function fillPostFormWithoutContent($form): void
	{
		$form = $this->fillFormWithFilter($form, "content");
	}

	private function fillPostFormWithoutTitle($form): void
	{
		$form = $this->fillFormWithFilter($form, "title");
	}

	private function fillPostFormWithValidData($form): void
	{
		$form = $this->fillForm($form);
	}
}