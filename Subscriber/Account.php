<?php

namespace FroshShareBasket\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Components_Session_Namespace as Session;
use FroshShareBasket\Components\Service\CustomerBasketServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use FroshShareBasket\Components\ShareBasketService;

class Account implements SubscriberInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ContextServiceInterface
     */
    private $contextService;

    private $customerBasketService;

    public function __construct(
        Session $session,
        ContextServiceInterface $contextService,
        ShareBasketService $shareBasketService,
        CustomerBasketServiceInterface $customerBasketService
    ) {
        $this->session = $session;
        $this->contextService = $contextService;
        $this->shareBasketService = $shareBasketService;
        $this->customerBasketService = $customerBasketService;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_Frontend_Account_Carts' => 'onActionFrontendAccountCarts',
            'Shopware_Modules_Admin_Login_FilterResult' => 'onAdminLoginFilterResult',
        ];
    }

    public function onActionFrontendAccountCarts(\Enlight_Event_EventArgs $args)
    {
        /**@var $controller Shopware_Controllers_Frontend_Account */
        $controller = $args->getSubject();
        $view = $controller->View();
        $request = $controller->Request();

        $view->froshSavedCarts = $this->customerBasketService->getList(
            $this->session->offsetGet('sUserId'),
            $this->contextService->getShopContext()
        );

        return true;
    }

    public function onAdminLoginFilterResult(\Enlight_Event_EventArgs $args)
    {
        // If the login was not successful we wont do anything
        if ($args->get('error')) {
            return;
        }

        // If a basket was saved, also save it in the customers account
        if ($this->session->offsetExists('froshShareBasketHash')) {
            $this->shareBasketService->saveBasket();
        }
    }
}
