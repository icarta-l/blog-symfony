<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use Symfony\Component\DomCrawler\Form;

class AdminControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests;

	private $title = "Test";
	private $description = "My awesome description";
	private $fields = ["title", "description"];

	private string $categoryCreationRouteName = "admin_create_category";

	/**
	 * Tests for "admin_create_category" route
	 */
	public function testCreateCategoryIsNotAllowedIfNotLoggedIn(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->categoryCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
	}

	public function testCreateCategoryRedirectsToLoginPage(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->categoryCreationRouteName));
		$crawler = $this->client->followRedirect();
		$this->assertSame("login", \basename($crawler->getUri()));
	}

	public function testCreateCategoryIsNotAllowedIfNotAdmin(): void
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->categoryCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
	}

	public function testCreateCategoryPageIsUp(): void
	{
		$this->setUpUser("admin");
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->categoryCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testCreateCategoryWorks(): void
	{
		$this->setUpUser("admin");
		$form = $this->getForm($this->categoryCreationRouteName, "Save");
		$this->fillCategoryFormWithValidData($form);
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="category-successfully-created"]')->count());
	}

	public function testCreateCategoryRedirectsAfterSubmitting(): void
	{
		$this->setUpUser("admin");
		$form = $this->getForm($this->categoryCreationRouteName, "Save");
		$this->fillCategoryFormWithValidData($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);	
	}

	private function getCategoryCreationForm(): Form
	{
		return $this->getForm($this->categoryCreationRouteName, "Save");
	}

	private function fillCategoryFormWithValidData(Form $form): void
	{
		$this->fillForm($form, "category");
	}
}