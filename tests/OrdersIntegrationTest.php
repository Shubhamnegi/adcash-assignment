<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrdersIntegrationTest extends WebTestCase
{

    public function testCreateOrder()
    {
        $client = static::createClient();

        $client->request("POST", "/api/order/", [
            "userId" => 1,
            "productId" => 1,
            "quantity" => 2],
            [],
            [
                "HTTP_CONTENT_TYPE" => "application/json"
            ]);
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetOrderByUser()
    {
        $client = static::createClient();

        $client->request("GET", "/api/order/?getby=user&name=Rimsha", [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);
        $result = $client->getResponse()->getContent();

        $result = json_decode($result, true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey("body", $result);
        $this->assertGreaterThan(0, count($result["body"]));
    }

    public function testGetOrderByProduct()
    {
        $client = static::createClient();

        $client->request("GET", "/api/order/?getby=product&name=fanta", [], [], ["HTTP_CONTENT_TYPE" => "application/json"]);
        $result = $client->getResponse()->getContent();

        $result = json_decode($result, true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey("body", $result);
        $this->assertGreaterThan(0, count($result["body"]));
    }

}