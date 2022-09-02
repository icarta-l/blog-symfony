<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class HomeControllerTest extends WebTestCase
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