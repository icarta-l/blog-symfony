<?php

namespace App\Tests\Entity;

use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
	public function testSetRemainingProperties(): void
	{
		$post = new Post();

		$post->setTitle("Test");
		$user = $this->createMock("App\Entity\User");
		$post->setRemainingProperties($user);
		$date_test = new \DateTimeImmutable("now", new \DateTimeZone("Europe/Rome"));

		$this->assertInstanceOf(User::class, $post->getAuthor());
		$this->assertSame("test", $post->getSlug());

		$date_post = new \DateTimeImmutable($post->getPublishedAt(), new \DateTimeZone("Europe/Rome"));

		$this->assertSame($date_test->format("Y"), $date_post->format("Y"));
		$this->assertSame($date_test->format("m"), $date_post->format("m"));
		$this->assertSame($date_test->format("d"), $date_post->format("d"));
		$this->assertSame($date_test->format("H"), $date_post->format("H"));
		$this->assertSame($date_test->format("i"), $date_post->format("i"));
	}

	public function testSetRemainingPropertiesReturnsError(): void
	{
		$post = new Post();
		$this->expectException("TypeError");
		$post->setRemainingProperties(new Post());
	}

	public function testSetRemainingPropertiesDoesntWorkWithoutTitle(): void
	{
		$post = new Post();
		$user = $this->createMock("App\Entity\User");
		$this->expectException("Error");
		$post->setRemainingProperties($user);
	}
}