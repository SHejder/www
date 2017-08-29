<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use AppBundle\Configuration\AppECommerceConfiguration;
use AppBundle\Configuration\AppOrderConfiguration;
use AppBundle\Entity\Order\Call;
use AppBundle\Sms\SmsException;
use AppBundle\Sms\SmsSender;
use Darvin\ECommerceBundle\Event\CheckoutEvent;
use Darvin\ECommerceBundle\Event\Events as ECommerceEvents;
use Darvin\OrderBundle\Event\Events as OrderEvents;
use Darvin\OrderBundle\Event\OrderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send SMS event subscriber
 */
class SendSmsSubscriber implements EventSubscriberInterface
{
    /**
     * @var \AppBundle\Configuration\AppECommerceConfiguration
     */
    private $ecommerceConfig;

    /**
     * @var \AppBundle\Configuration\AppOrderConfiguration
     */
    private $orderConfig;

    /**
     * @var \AppBundle\Sms\SmsSender
     */
    private $smsSender;

    /**
     * @param \AppBundle\Configuration\AppECommerceConfiguration $ecommerceConfig E-commerce configuration
     * @param \AppBundle\Configuration\AppOrderConfiguration     $orderConfig     Order configuration
     * @param \AppBundle\Sms\SmsSender                           $smsSender       SMS sender
     */
    public function __construct(AppECommerceConfiguration $ecommerceConfig, AppOrderConfiguration $orderConfig, SmsSender $smsSender)
    {
        $this->ecommerceConfig = $ecommerceConfig;
        $this->orderConfig = $orderConfig;
        $this->smsSender = $smsSender;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ECommerceEvents::POST_CHECKOUT => 'sendCartCheckoutSms',
            OrderEvents::POST_ORDER_SUBMIT => 'sendOrderSms',
        ];
    }

    /**
     * @param \Darvin\ECommerceBundle\Event\CheckoutEvent $event Event
     */
    public function sendCartCheckoutSms(CheckoutEvent $event)
    {
        $order = $event->getOrder();

        try {
            $this->smsSender->sendSms($this->ecommerceConfig->getSmsNotificationPhones(), 'sms.cart_checkout', [
                $order->getUserFullName(),
                $order->getUserPhone(),
            ]);
        } catch (SmsException $ex) {
        }
    }

    /**
     * @param \Darvin\OrderBundle\Event\OrderEvent $event Event
     */
    public function sendOrderSms(OrderEvent $event)
    {
        $order = $event->getOrder();

        if ($order instanceof Call) {
            try {
                $this->smsSender->sendSms($this->orderConfig->getSmsNotificationPhones(), 'sms.order_call', [
                    $order->getName(),
                    $order->getPhone(),
                ]);
            } catch (SmsException $ex) {
            }
        }
    }
}
