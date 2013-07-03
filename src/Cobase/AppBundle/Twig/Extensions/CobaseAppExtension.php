<?php

namespace Cobase\AppBundle\Twig\Extensions;

class CobaseAppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'show_maxlen' => new \Twig_Filter_Method($this, 'showMaxLen'),
        );
    }

    public function showMaxLen($value, $maxlen, $ending = '')
    {
        if (strlen($value) > $maxlen) {
            return substr($value, 0, $maxlen) . $ending;
        } else {
            return $value;
        }
        return $string;
    }
    
    public function getName()
    {
        return 'Cobase_app_extension';
    }
}