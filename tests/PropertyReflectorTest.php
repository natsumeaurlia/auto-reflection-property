<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Resources\GithubUser;

class PropertyReflectorTest extends TestCase
{
    private $json;

    public function setUp(): void
    {
        // alternative expression for test
        // curl -H "Accept: application/vnd.github.v3+json" https://api.github.com/users/{USERNAME}
        $this->json = file_get_contents(dirname(__FILE__) . '/resources/github-users.json');
    }

    public function test_should_set_property_pass_json()
    {
        $user = new GithubUser($this->json);
        $json_user = json_decode($this->json);

        $this->assertEquals($user->id, $json_user->id);
        $this->assertEquals($user->login, $json_user->login);
        $this->assertFalse(isset($user->testProperty));
    }

    public function test_should_set_property_pass_stdClass()
    {
        $stdClass = json_decode($this->json);
        $user = new GithubUser($stdClass);

        $this->assertEquals($user->id, $stdClass->id);
        $this->assertEquals($user->login, $stdClass->login);
        $this->assertFalse(isset($user->testProperty));
    }

    public function test_should_set_property_pass_array()
    {
        $arr = json_decode($this->json, true);
        $user = new GithubUser($arr);

        $this->assertEquals($user->id, $arr['id']);
        $this->assertEquals($user->login, $arr['login']);
        $this->assertFalse(isset($user->testProperty));
    }

    public function test_should_set_public()
    {
        $user = new GithubUser($this->json);

        $this->assertTrue(isset($user->id));
        $this->assertTrue(isset($user->login));
    }

    public function test_should_set_protected()
    {
        $stdClass = json_decode($this->json);
        $user = new GithubUser($this->json);

        $this->assertEquals($user->getNodeId(), $stdClass->node_id);
        $this->assertEquals($user->getAvatarUrl(), $stdClass->avatar_url);
    }

    public function test_should_not_set_private()
    {
        $user = new GithubUser($this->json);

        $this->assertNull($user->getName());
    }

    public function test_should_create_named_property()
    {
        // created_at property is DateTime
        $user = new GithubUser($this->json);

        $this->assertNotNull($user->created_at);
        $this->assertTrue($user->created_at instanceof \DateTime);
    }
}