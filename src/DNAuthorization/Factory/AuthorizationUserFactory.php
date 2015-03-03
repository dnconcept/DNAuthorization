<?php

namespace DNAuthorization\Factory;

use DNAuthorization\AuthorizationUser;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ModuleOptionsFactory
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class AuthorizationUserFactory implements FactoryInterface {

   public function createService(ServiceLocatorInterface $sm) {
      $options = $sm->get("DNAuthorization\ModuleOptions");
      $authUser = new AuthorizationUser($sm->get('Zend\Authentication\AuthenticationService'), $sm->get("DNAuthorization\Acl"));
      $authUser->setDefaultRole($options->getDefaultRole());
      return $authUser;
   }

}
