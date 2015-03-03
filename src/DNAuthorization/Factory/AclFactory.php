<?php

namespace DNAuthorization\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ModuleOptionsFactory
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class AclFactory implements FactoryInterface {

   public function createService(ServiceLocatorInterface $sm) {
      $acl = new \Zend\Permissions\Acl\Acl();
      $acl->addRole("guest");
      return $acl;
   }

}
