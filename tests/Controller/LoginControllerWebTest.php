<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use Symfony\Component\DomCrawler\Form;

class LoginControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests;

	private string $userRegistrationRouteName = "register";
	private array $fields = ["username", "password", "email", "roles"];
	private string $username = "NewTest";
	private string $password = "myPotates";
	private string $email = "newtest@test.com";
	private string $roles = "ROLE_USER";

	/**
	 * Test for "register"
	 */
	public function testRegisterPageIsUp(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->userRegistrationRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testRegisterUserWorks(): void
	{
		$form = $this->fillForm($this->getForm($this->userRegistrationRouteName, "Register User"), "user");
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="user-successfully-created"]')->count());
	}
}