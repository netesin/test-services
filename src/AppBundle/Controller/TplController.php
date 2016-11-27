<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TplController extends Controller
{
    /**
     * @Route("/tpl/order.html", name="tpl.order")
     */
    public function getOrderTplAction()
    {
        return $this->render('AppBundle:Tpl:order.html.twig');
    }

    /**
     * @Route("/tpl/admin.html", name="tpl.admin")
     */
    public function getAdminTplAction()
    {
        return $this->render('AppBundle:Tpl:admin.html.twig');
    }

    /**
     * @Route("/tpl/modal/discount.html", name="tpl.modal.discount")
     */
    public function getModalDiscountTplAction()
    {
        return $this->render('@App/Tpl/modal/discount.html.twig');
    }

    /**
     * @Route("/tpl/modal/service.html", name="tpl.modal.service")
     */
    public function getModalServiceTplAction()
    {
        return $this->render('@App/Tpl/modal/service.html.twig');
    }
}
