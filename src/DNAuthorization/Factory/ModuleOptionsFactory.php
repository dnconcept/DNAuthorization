<?php

namespace DNAuthorization\Factory;

use DNAuthorization\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ModuleOptionsFactory
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class ModuleOptionsFactory implements FactoryInterface {

   public function createService(ServiceLocatorInterface $sm) {
      $appConfig = $sm->get("Config");
      $config = isset($appConfig["dn-authorization"]) ? $appConfig["dn-authorization"] : [];
      return new ModuleOptions($config);
   }

}
