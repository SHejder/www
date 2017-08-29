<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\Review;

use Darvin\ReviewBundle\Entity\Review;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Darvin\ReviewBundle\Repository\ReviewRepository")
 */
class AppReview extends Review
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[0-9\-\+\(\)\ ]{3,15}$/",
     *     message="Поле не является допустимым форматом телефонного номера. Необходимый формат: +7 (000) 123-45-67"
     * )
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $annotation;

    /**
     * AppReview constructor.
     * @param bool   $new
     * @param string $locale
     */
    public function __construct($new = true, $locale = "")
    {
        parent::__construct($new, $locale);
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
     *
     * @return AppReview
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation
     *
     * @return AppReview
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }
}
