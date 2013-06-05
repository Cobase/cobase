<?php
namespace Cobase\DemoBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CatModel
{
    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "You must provide your name"
     * )
     * @Assert\Length(
     *      min = "2",
     *      max = "40",
     *      minMessage = "The name must contain at least {{ limit }} character|The name must contain at least {{ limit }} characters",
     *      maxMessage = "The name must not be longer than {{ limit }} character|The name must not be longer than {{ limit }} characters"
     * )
     *
     */
    protected $name;

    /**
     * @var int
     * @Assert\NotBlank(
     *      message = "You must provide your age"
     * )
     *
     * @Assert\Regex(
     *      pattern = "/^([0-9][0-9]*)$/",
     *      message = "{{ value }} is not a number"
     * )
     *
     * @Assert\Min(
     *      limit = "1",
     *      message = "Surely you must be older than that"
     * ),
     *
     * @Assert\Max(
     *      limit = "40",
     *      message = "Even as a cat, you cannot possibly be THAT old"
     * )
     *
     */
    protected $age;

    /**
     * @param int $age
     *
     * @return CatModel
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param string $name
     * @return CatModel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
