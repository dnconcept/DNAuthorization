<?php

namespace DNAuthorization;

use Zend\Mvc\MvcEvent;

/**
 * Description of DNAuthorizationModule
 * 
 * This module handle authorization on routing
 * 
 */
class Module {

  public function onBootstrap(MvcEvent $event) {
    $application = $event->getApplication();
    $eventManager = $application->getEventManager();
    $auth = $application->getServiceManager()->get("DNAuthorization\Authorization");
    $eventManager->attach(MvcEvent::EVENT_ROUTE, [$auth, 'checkAuthorization'], -100);
    $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$auth, 'onDispatchError'], -999);
  }

  public function getAutoloaderConfig() {
    return [
        'Zend\Loader\StandardAutoloader' => [
            'namespaces' => [
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
            ],
        ],
    ];
  }

  public function getServiceConfig() {
    return [
        'invokables' => [
        ],
        'factories' => [
            'DNAuthorization\Acl' => 'DNAuthorization\Factory\AclFactory',
            'DNAuthorization\Authorization' => 'DNAuthorization\Factory\AuthorizationFactory',
            'DNAuthorization\AuthorizationUser' => 'DNAuthorization\Factory\AuthorizationUserFactory',
            'DNAuthorization\ModuleOptions' => 'DNAuthorization\Factory\ModuleOptionsFactory',
        ]
    ];
  }

}
