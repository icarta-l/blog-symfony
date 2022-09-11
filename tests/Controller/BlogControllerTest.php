<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use App\Tests\Tools\PostWriter;
use App\Entity\Post;

class BlogControllerTest extends WebTestCase
{
	use BaseSetupForWebTests;
	use PostWriter;

	private string $postCreationRouteName = "create_post";

	/**
	 * Tests for "create_post" route
	 */
	public function testCreatePostIsNotAllowedIfNotLoggedIn(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->postCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
	}

	public function testCreatePostRedirectsToLoginPage(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->postCreationRouteName));
		$crawler = $this->client->followRedirect();
		$this->assertSame("login", \basename($crawler->getUri()));
	}

	public function testCreatePostPageIsUp(): void
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->postCreationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testCreatePostWorks(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithValidData($form);
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="post-successfully-published"]')->count());
	}

	public function testCreatePostRedirectsAfterSubmitting(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithValidData($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);	
	}

	public function testCreatePostFailsWithoutTitle(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithoutTitle($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testCreatePostFailsWithoutContent(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithoutContent($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testCreatePostFailsWithoutSummary(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithoutSummary($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testCreatePostFailsWithoutCategories(): void
	{
		$this->setUpUser();
		$form = $this->getPostCreationForm();
		$this->fillPostFormWithoutCategories($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}