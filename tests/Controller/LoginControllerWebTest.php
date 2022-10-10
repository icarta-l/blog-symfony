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
	private string $userLoginRouteName = "login";
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

	public function testRegisterUserDoesntWorkWithoutUsername(): void
	{
		$form = $this->fillFormWithFilter($this->getForm($this->userRegistrationRouteName, "Register User"), "username", "user");
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testRegisterUserDoesntWorkWithoutPassword(): void
	{
		$form = $this->fillFormWithFilter($this->getForm($this->userRegistrationRouteName, "Register User"), "password", "user");
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	public function testRegisterUserDoesntWorkWithoutEmail(): void
	{
		$form = $this->fillFormWithFilter($this->getForm($this->userRegistrationRouteName, "Register User"), "email", "user");
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * Test for "login"
	 */
	public function testLoginPageIsUp(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->userLoginRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testLoginWorks(): void
	{
		$form = $this->fillForm($this->getForm($this->userRegistrationRouteName, "Register User"), "user");
		$this->client->submit($form);

		$form = $this->getForm($this->userLoginRouteName, "Login");
		$form["_username"] = $this->email;
		$form["_password"] = $this->password;
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertStringContainsString($this->username, $crawler->filterXPath('//section[@class="row row-home"]/div/p')->innerText());
	}

	public function testLoginDoesntWorkWithoutUsername(): void
	{
		$form = $this->fillForm($this->getForm($this->userRegistrationRouteName, "Register User"), "user");
		$this->client->submit($form);

		$form = $this->getForm($this->userLoginRouteName, "Login");
		$form["_password"] = $this->password;
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="login-error"]')->count());
	}

	public function testLoginDoesntWorkWithoutPassword(): void
	{
		$form = $this->fillForm($this->getForm($this->userRegistrationRouteName, "Register User"), "user");
		$this->client->submit($form);

		$form = $this->getForm($this->userLoginRouteName, "Login");
		$form["_username"] = $this->email;
		$this->client->submit($form);
		$crawler = $this->client->followRedirect();
		$this->assertSame(1, $crawler->filterXPath('//div[@class="login-error"]')->count());
	}
}