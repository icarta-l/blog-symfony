<?php

namespace App\Tests\Tools;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Entity\User;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;

trait BaseSetupForWebTests 
{
	private ?KernelBrowser $client = null;

	public function setUp(): void
	{
		$this->client = static::createClient();
		$this->urlGenerator = $this->client->getContainer()->get("router.default");
	}

	private function setUpUser($role = "user"): void
	{
		if ($role === "admin") {
			$email = "admin@test.com";
		} else {
			$email = "test.user@test.com";
		}
		$this->userRepository = $this->client->getContainer()->get("doctrine.orm.entity_manager")->getRepository(User::class);
		$this->user = $this->userRepository->findOneByEmail($email);
		$this->client->loginUser($this->user);
	}

	private function getForm(string $route_name, string $button_identifier): Form
	{
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($route_name));
		return $crawler->selectButton($button_identifier)->form();
	}

	private function fillForm(Form $form, string $prefix): Form
	{
		foreach ($this->fields as $field) {
			$field_name = $prefix . "[" . $field . "]";
			if (!$form->has($field_name)) {
				continue;
			}
			$form[$field_name] = $this->$field;
		}
		return $form;
	}

	private function fillFormWithFilter(Form $form, string $blacklisted_field, string $prefix): Form
	{
		if (in_array($blacklisted_field, $this->fields)) {
			$key = array_search($blacklisted_field, $this->fields);
			array_splice($this->fields, $key, 1);
		}
		return $this->fillForm($form, $prefix);
	}
}