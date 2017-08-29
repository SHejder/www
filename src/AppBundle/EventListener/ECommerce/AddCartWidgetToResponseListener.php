<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2017, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener\ECommerce;

use Darvin\ECommerceBundle\Cart\CartWidgetRenderer;
use Darvin\ECommerceBundle\Controller\CartController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Add e-commerce cart side widget to response event listener
 */
class AddCartWidgetToResponseListener
{
    /**
     * @var \Darvin\ECommerceBundle\Cart\CartWidgetRenderer
     */
    private $cartWidgetRenderer;

    /**
     * @param \Darvin\ECommerceBundle\Cart\CartWidgetRenderer $cartWidgetRenderer Cart widget renderer
     */
    public function __construct(CartWidgetRenderer $cartWidgetRenderer)
    {
        $this->cartWidgetRenderer = $cartWidgetRenderer;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event Event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        if (!$response instanceof JsonResponse
            || !$request->isXmlHttpRequest()
            || 0 !== strpos($request->get('_controller'), CartController::class.'::')
        ) {
            return;
        }

        $data = json_decode($response->getContent(), true);
        $data['cartSideWidget'] = $this->cartWidgetRenderer->renderCartWidget(':ecommerce:app_cart_widget.html.twig');
        $response->setContent(json_encode($data));
    }
}
