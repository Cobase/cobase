<?php

namespace Cobase\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CobaseUserBundle extends Bundle
{
    public function getParent()
    {
        return "FOSUserBundle";
    }
}
