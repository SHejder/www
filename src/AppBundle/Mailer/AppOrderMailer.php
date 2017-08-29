<?php
/**
 * Created by Alex
 */
namespace AppBundle\Mailer;

use AppBundle\Entity\Order\RequestADemo;
use Darvin\OrderBundle\Configuration\Configuration;
use Darvin\OrderBundle\Configuration\OrderType;
use Darvin\OrderBundle\Entity\AbstractOrder;
use Darvin\OrderBundle\Mailer\OrderMailer;
use Darvin\UserBundle\Entity\BaseUser;
use Darvin\Utils\Mailer\MailerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Order mailer
 */
class AppOrderMailer extends OrderMailer
{
    /**
     * @param \Darvin\Utils\Mailer\MailerInterface               $genericMailer Generic mailer
     * @param \Darvin\OrderBundle\Configuration\Configuration    $orderConfig   Order configuration
     * @param \Symfony\Component\Templating\EngineInterface      $templating    Templating
     * @param \Symfony\Component\Translation\TranslatorInterface $translator    Translator
     */
    public function __construct(MailerInterface $genericMailer, Configuration $orderConfig, EngineInterface $templating, TranslatorInterface $translator)
    {
        parent::__construct($genericMailer, $orderConfig, $templating, $translator);
    }

    /**
     * @param \Darvin\OrderBundle\Configuration\OrderType $orderType Order type
     * @param \Darvin\OrderBundle\Entity\AbstractOrder    $order     Order
     * @param \Darvin\UserBundle\Entity\BaseUser          $user      User
     */
    public function sendServicePostSubmitEmails(OrderType $orderType, AbstractOrder $order, BaseUser $user = null)
    {
        $to = $this->orderConfig->getNotificationEmailsByOrderType($order->getType());

        if (empty($to)) {
            return;
        }

        /** @var $order RequestADemo */

        $body = $this->templating->render($orderType->getEmailTemplate(), [
            'order'      => $order,
            'order_type' => $orderType,
            'user'       => $user,
        ]);

        $this->genericMailer->send($orderType->getEmailSubject(), $body, $to, [
            '%order_type%' => $this->translator->trans($orderType->getTitle(), [], 'admin'),
        ], 'text/html');
    }
}
