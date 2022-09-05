<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;

class HomeControllerTest extends WebTestCase
{
	use BaseSetupForWebTests;

	public function testHomepageIsUp()
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("home"));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testMenuShowsLoginPageWhenNotLoggedIn()
	{
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("home"));
		$this->assertSame(1, $crawler->filterXPath('//a[@class="main-navigation-link" and @href="/login"]')->count());
	}

	public function testMenuDoesntShowLoginPageWhenLoggedIn()
	{
		$this->setUpUser();
		$crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate("home"));
		$this->assertSame(0, $crawler->filterXPath('//a[@class="main-navigation-link" and @href="/login"]')->count());
	}
}