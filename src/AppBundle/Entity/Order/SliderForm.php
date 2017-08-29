<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13.07.2017
 * Time: 13:25
 */

namespace AppBundle\Entity\Order;

use Darvin\OrderBundle\Entity\AbstractOrder;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SliderForm
 *
 * @ORM\Entity
 */
class SliderForm extends AbstractOrder
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[0-9\-\+\(\)\ ]{3,15}$/",
     *     message="Введён некорректный телефонный номер.")
     */
    protected $phone;

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}
