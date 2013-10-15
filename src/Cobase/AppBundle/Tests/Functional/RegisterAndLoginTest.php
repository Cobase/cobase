<?php

namespace Cobase\AppBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterAndLoginTest extends WebTestCase
{
    protected $realName;
    protected $username;
    protected $password;
    protected $email;

    protected function setUp()
    {
        $this->realName = 'test' . time();
        $this->email = 'test@test' . time() . ".com";
        $this->username = 'test' . time();
        $this->password = 'test' . time();
    }
    
    /**
     * @test
     *
     * @group functional
     */
    public function testThatLoginIsRequired()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Login")')->count()
        );
    }
    
    /**
     * @test
     *
     * @group functional
     */
    public function testThatLoginExists()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/login');
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Username")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Password")')->count()
        );

    }
}