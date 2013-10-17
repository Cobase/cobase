<?php
namespace Cobase\Component;

class AppInfo 
{
    /**
     * @var string
     */
    private $siteName;

    /**
     * @var EmailUser
     */
    private $siteAdmin;

    /**
     * @param EmailUser $siteAdmin
     * @param string $siteName
     */
    function __construct(EmailUser $siteAdmin, $siteName)
    {
        $this->siteAdmin = $siteAdmin;
        $this->siteName = $siteName;
    }

    /**
     * @return EmailUser
     */
    public function getSiteAdmin()
    {
        return $this->siteAdmin;
    }

    /**
     * @return string
     */
    public function getSiteName()
    {
        return $this->siteName;
    }
}
