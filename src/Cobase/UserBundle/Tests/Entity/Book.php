<?php
namespace Cobase\UserBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Likeable;

class Book implements Likeable
{
    /**
     * @return string
     */
    public function getLikeableType()
    {
        return 'book';
    }

    /**
     * @return mixed
     */
    public function getLikeableId()
    {
        return 1;
    }
}
