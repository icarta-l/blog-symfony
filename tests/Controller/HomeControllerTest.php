<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class HomeControllerTest extends WebTestCase
{
	private ?KernelBrowser $client = null;

	public function setUp(): void
	{
		$this->client = static::createClient();
	}

	public function testHomepageIsUp()
	{
		$urlGenerator = $this->client->getContainer()->get("router.default");
		$this->client->request(Request::METHOD_GET, $urlGenerator->generate("home"));
	}
}