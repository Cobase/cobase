<?php

namespace Cobase\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CobaseApiBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
