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

	private function setUpUser(): void
	{
		$this->userRepository = $this->client->getContainer()->get("doctrine.orm.entity_manager")->getRepository(User::class);
		$this->user = $this->userRepository->findOneByEmail("test.user@test.com");
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
}