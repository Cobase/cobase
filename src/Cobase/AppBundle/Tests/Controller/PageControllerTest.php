<?php

namespace Cobase\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/about');

        $this->assertEquals(1, $crawler->filter('h2:contains("About YouHighFiveMe")')->count());
    }
    
    public function testIndex()
    {
        $client = static::createClient();
    
        $crawler = $client->request('GET', '/');
    
        $this->assertEquals(1, $crawler->filter('h2:contains("Latest events")')->count());
    }
    
    public function testContact()
    {
        $client = static::createClient();
    
        $crawler = $client->request('GET', '/contact');
    
        $this->assertEquals(1, $crawler->filter('h2:contains("Contact us")')->count());
    
        // Select based on button value, or id or name for buttons
        $form = $crawler->selectButton('Submit')->form();

        $form['cobase_appbundle_enquirytype[name]']       = 'name';
        $form['cobase_appbundle_enquirytype[email]']      = 'email@email.com';
        $form['cobase_appbundle_enquirytype[subject]']    = 'Subject';
        $form['cobase_appbundle_enquirytype[body]']       = 'The comment body must be at least 50 characters long as there is a validation constrain on the Enquiry entity';
    
        $crawler = $client->submit($form);

        // Check email has been sent
        if ($profile = $client->getProfile())
        {
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');

            // Only 1 message should have been sent
            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

            // Get the first message
            $messages = $swiftMailerProfiler->getMessages();
            $message  = array_shift($messages);

            $appEmail = $client->getContainer()->getParameter('cobase_app.emails.contact_email');
            // Check message is being sent to correct address
            $this->assertArrayHasKey($appEmail, $message->getTo());
        }

        // Need to follow redirect
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('.flash-notice-success:contains("Your contact enquiry was successfully sent. Thank you!")')->count() > 0);
    }
    
}