<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use App\Entity\Post;

class BlogControllerTest extends WebTestCase
{
	use BaseSetupForWebTests;

	private string $validPostTitle = "Test";

	public function testCreatePostIsNotAllowedIfNotLoggedIn(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
	}

	public function testCreatePostRedirectsToLoginPage(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$crawler = $this->client->followRedirect();
		$this->assertSame("login", \basename($crawler->getUri()));
	}

	public function testCreatePostPageIsUp(): void
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testCreatePostWorks(): void
	{
		$this->setUpUser();
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$form = $crawler->selectButton("Publish")->form();
		$this->fillCreatePostFormWithValidData($form);
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="post-successfully-published"]')->count());
	}

	public function testCreatePostRedirectsAfterSubmitting(): void
	{
		$this->setUpUser();
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$form = $crawler->selectButton("Publish")->form();
		$this->fillCreatePostFormWithValidData($form);
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);	
	}

	private function setUpPostRepository(): void
	{
		$this->postRepository = $this->client->getContainer()->get("doctrine.orm.entity_manager")->getRepository(Post::class);
	}

	private function fillCreatePostFormWithValidData(&$form)
	{
		$form["post[title]"] = "Test";
		$form["post[summary]"] = "A fake summary to publish the post";
		$form["post[categories]"] = [1];
		$form["post[content]"] = "Here is my fake new content to test the post publishing logic.";
	}
}