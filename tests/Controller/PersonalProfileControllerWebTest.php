<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Tools\BaseSetupForWebTests;

class PersonalProfileControllerWebTest extends WebTestCase
{
	use BaseSetupForWebTests;

	private string $profileEditingRouteName = "edit_profile";

	/**
	 * Test profile editing page is up
	 */
	public function testProfileEditingPageIsUp(): void
	{
		$this->setUpUser();
		$this->client->request(Request::METHOD_GET, $this->urlGenerator->generate($this->profileEditingRouteName));
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}
}