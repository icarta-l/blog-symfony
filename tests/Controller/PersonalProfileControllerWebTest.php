<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;
use App\Tests\Tools\ProfileEditor;

class PersonalProfileControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests, ProfileEditor;

	private string $profileEditingRouteName = "edit_profile";

	/**
	 * Tests for "edit_profile" route
	 */
	public function testProfileEditingIsNotAllowedIfNotLoggedIn(): void
	{
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->profileEditingRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
	}

	public function testProfileEditingPageIsUp(): void
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->profileEditingRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testCreateCategoryWorks(): void
	{
		$this->setUpUser("admin");
		$form = $this->getForm($this->profileEditingRouteName, "Edit Profile");
		$this->fillProfileEditingFormWithValidData($form);
		$crawler = $this->client->submit($form);
		$form = $crawler->selectButton("Edit Profile")->form();
		$this->assertSame($form->get("form[username]")->getValue(), $this->username);
	}

	public function testProfileEditingFailsWithoutUsername(): void
	{
		$this->setUpUser("admin");
		$form = $this->getForm($this->profileEditingRouteName, "Edit Profile");
		$form["form[username]"] = "";
		$this->client->submit($form);
		$this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}