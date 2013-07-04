<?php
namespace Cobase\AppBundle\Entity;

interface Likeable
{
    /**
     * @return string
     */
    public function getLikeableType();

    /**
     * @return mixed
     */
    public function getLikeableId();
}
