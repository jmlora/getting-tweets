<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;

class TweetsControllerTest extends WebTestCase
{
    public function testGetTweets()
    {
        $client = static::createClient();

        $client->request('GET', '/tweets?username=nasa');
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        
        // $this->assertEquals('{"success":"true"}', $response->getContent());
        $this->assertNotEmpty($response->getContent());
    }

    public function testGetNonexistentUserTweets()
    {
        $client = static::createClient();

        $client->request('GET', '/tweets?username=nasaerror');
        $response = $client->getResponse();
        $this->assertTrue($response->isServerError());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        
        // $this->assertEquals('{"success":"true"}', $response->getContent());
        $this->assertNotEmpty($response->getContent());
    }
}
