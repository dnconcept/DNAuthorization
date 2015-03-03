<?php

namespace DNAuthorization\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ModuleOptionsFactory
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class AuthorizationFactory implements FactoryInterface {

   public function createService(ServiceLocatorInterface $serviceLocator) {
      $acl =  $serviceLocator->get("DNAuthorization\Acl");
      $authUser =  $serviceLocator->get("DNAuthorization\AuthorizationUser");
      return new \DNAuthorization\Authorization($acl, $authUser);
   }

}
