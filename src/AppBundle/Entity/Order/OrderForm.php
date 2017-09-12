<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 12.09.2017
 * Time: 15:39
 */

namespace AppBundle\Entity\Order;


use Darvin\OrderBundle\Entity\AbstractOrder;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Call
 *
 * @ORM\Entity
 */


class OrderForm extends AbstractOrder
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
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     */
    protected $message;

    public function getProperties()
    {
        return [
            'name'  => $this->name,
            'phone' => $this->phone,
            'email' => $this ->email,
            'message' => $this -> message
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
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message message
     *
     * @return OrderForm
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}