<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;

class AdminControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests;

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
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->postCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}
}