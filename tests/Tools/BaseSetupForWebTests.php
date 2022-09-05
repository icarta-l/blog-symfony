<?php

namespace App\Tests\Tools;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Entity\User;

trait BaseSetupForWebTests {
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
}