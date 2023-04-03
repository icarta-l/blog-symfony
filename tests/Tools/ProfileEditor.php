<?php

namespace App\Tests\Tools;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait ProfileEditor
{
    private $username = "Concombre";
	private $fields = ["username"];


	private function fillProfileEditingFormWithValidData(Form $form): void
	{
		$this->fillForm($form, "form");
	}

    private function fillProfileEditingFormWithoutUsername(Form $form): void
    {
        $this->fillFormWithFilter($form, "username", "form");
    }
}