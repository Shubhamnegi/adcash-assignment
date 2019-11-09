<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersIntegrationTest extends WebTestCase
{
    public function testListUsers()
    {
        $client = static::createClient();

        $client->request("GET", "/api/user/", [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);
        $result = $client->getResponse()->getContent();

        $result = json_decode($result, true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey("body", $result);
        $this->assertGreaterThan(0, count($result["body"]));


    }
}