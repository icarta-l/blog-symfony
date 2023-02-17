<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use Symfony\Component\DomCrawler\Form;
use App\Tests\Tools\CategoryWriter;

class AdminControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests, CategoryWriter;

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
		$form = $this->getCategoryCreationForm();
		$this->fillCategoryFormWithValidData($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);	
	}

	public function testCreateCategoryFailsWithoutTitle(): void
	{
		$this->setUpUser("admin");
		$form = $this->getCategoryCreationForm();
		$this->fillCategoryFormWithoutTitle($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testCreateCategoyFailsWithoutDescription(): void
	{
		$this->setUpUser("admin");
		$form = $this->getCategoryCreationForm();
		$this->fillCategoryFormWithoutDescription($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}