<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;

class BlogControllerTest extends WebTestCase
{
	use BaseSetupForWebTests;

	public function testCreatePostIsNotAllowedIfNotLoggedIn()
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
	}

	public function testCreatePostRedirectsToLoginPage()
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$crawler = $this->client->followRedirect();
		$this->assertSame("login", \basename($crawler->getUri()));
	}

	public function testCreatePostPageIsUp()
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("create_post"));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}
}