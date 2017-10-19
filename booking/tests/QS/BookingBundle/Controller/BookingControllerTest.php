<?php

namespace Tests\QS\BookingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Commander vos billets', $crawler->filter('#event-wrap a')->text());
    }
}
