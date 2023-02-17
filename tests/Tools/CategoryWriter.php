<?php

namespace App\Tests\Tools;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait CategoryWriter
{
    private $title = "Test";
	private $description = "My awesome description";
	private $fields = ["title", "description"];


    private function getCategoryCreationForm(): Form
	{
		return $this->getForm($this->categoryCreationRouteName, "Save");
	}

	private function fillCategoryFormWithValidData(Form $form): void
	{
		$this->fillForm($form, "category");
	}

    private function fillCategoryFormWithoutTitle(Form $form): void
    {
        $this->fillFormWithFilter($form, "title", "category");
    }

    private function fillCategoryFormWithoutDescription(Form $form): void
    {
        $this->fillFormWithFilter($form, "description", "category");
    }
}