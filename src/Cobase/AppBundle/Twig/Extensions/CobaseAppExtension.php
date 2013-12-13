<?php

namespace Cobase\AppBundle\Twig\Extensions;

class CobaseAppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'show_maxlen' => new \Twig_Filter_Method($this, 'showMaxLen'),
            'show_newflag' => new \Twig_Filter_Method($this, 'showNewFlag'),
        );
    }

    /**
     * Crop string at the given length and append ending string.
     * 
     * @param $value
     * @param $maxlen
     * @param string $ending
     * @return string
     */
    public function showMaxLen($value, $maxlen, $ending = '')
    {
        if (strlen($value) > $maxlen) {
            return substr($value, 0, $maxlen) . $ending;
        } else {
            return $value;
        }
        return $string;
    }

    /**
     * Render new flag if given date is less than five days
     * 
     * @param \DateTime $dateTime
     * @return string
     * @throws \Exception
     */
    public function showNewFlag(\DateTime $dateTime)
    {
        $delta = time() - $dateTime->getTimestamp();
        if ($delta < 0)
        throw new \Exception("Ago is unable to handle dates in the future");
        
        $newFlagString = "";
        $time = floor($delta / 86400);
        
        if ($time < 2) {
            $newFlagString = '<span class="label label-info">New</span>';
        }
        
        return $newFlagString;
    }
        
    public function getName()
    {
        return 'Cobase_app_gravatar';
    }
}