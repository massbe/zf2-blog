<?php


namespace Application\Controller;

use Zend\Mvc\MvcEvent;

class BaseAdminController extends BaseController
{
    public function onDispatch(MvcEvent $e)
    {
        return parent::onDispatch($e);
    }
}