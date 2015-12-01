<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Application\Model\Edital;
use Application\Model\EditalTable;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceCOnfig(){
        return[
                'factories' => [
                          'Application\Model\EditalTable' => function($sm){
                              $tableGateway = $sm->get('EditalTableGateway');
                              $table = new EditalTable($tableGateway);
                              return $table;
                          },
                          'EditalTableGateway' => function($sm){
                              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                              $resultSetPrototype = new ResultSet();
                              $resultSetPrototype->setArrayObjectPrototype(new Edital());
                              return new TableGateway('editais',$dbAdapter,
                                  null,$resultSetPrototype);
                          },


                ]

        ];
    }

}
