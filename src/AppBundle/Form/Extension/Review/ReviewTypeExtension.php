<?php
/**
 * @author    Alexander Volodin <mr-stanlik@yandex.ru>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Extension\Review;

use AppBundle\Entity\Review\AppReview;
use AppBundle\Form\Type\PrivacyPolicyConfirmCheckboxType;
use Darvin\ImageBundle\Form\Type\Image\ImageType;
use Darvin\ReviewBundle\Entity\ReviewImage;
use Darvin\ReviewBundle\Form\Type\ReviewType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * My testimonial form type
 */
class ReviewTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Ваше имя',
                ),
            ))
            ->add('phone', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Номер телефона',
                ),
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email',
                ),
            ))
            ->add('text', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Текст отзывa',
                ),
            ))
            ->add('image', ImageType::class, [
                'data_class' => ReviewImage::class,
            ])
            ->add('policy', PrivacyPolicyConfirmCheckboxType::class);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', AppReview::class);
    }


    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ReviewType::class;
    }
}