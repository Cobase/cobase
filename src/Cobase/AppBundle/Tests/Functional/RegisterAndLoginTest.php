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

    /**
     * @test
     *
     * @group functional
     */
    public function testThatRegistrationAndLoginWorks()
    {
        // Test registration
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
        $crawler = $client->followRedirect();
        
        $form = $crawler->selectButton('Register')->form();
  
        $crawler = $client->submit(
            $form,
            array(
                'fos_user_registration_form[name]' => $this->realName,
                'fos_user_registration_form[email]' => $this->email,
                'fos_user_registration_form[username]' => $this->username,
                'fos_user_registration_form[plainPassword][first]' => $this->password,
                'fos_user_registration_form[plainPassword][second]' => $this->password,
            )
        );

        $crawler = $client->followRedirect();
        
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("your account is now activated")')->count()
        );
    }
}