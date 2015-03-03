<?php

namespace DNAuthorizationTest\Module;

use PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of ContactControllerTest
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class ModuleTest extends PHPUnit {

   private $serviceManager;

   protected function setUp() {
      $this->serviceManager = \Bootstrap::getServiceManager();
   }

   public function testConfig() {
      $config = $this->serviceManager->get('Config');
      $this->assertTrue(isset($config["dn-authorization"]));
   }

   /**
    * Tests des services disponibles
    */
   public function testHasServices() {
      $this->assertTrue($this->serviceManager->has('DNAuthorization\ModuleOptions'));
      $this->assertTrue($this->serviceManager->has('DNAuthorization\AuthorizationUser'));
      $this->assertTrue($this->serviceManager->has('DNAuthorization\Acl'));
   }

   /**
    * Tests des services disponibles
    */
   public function testGetModuleOptions() {
      $ModuleOptions = $this->serviceManager->get('DNAuthorization\ModuleOptions');
      $this->assertInstanceOf("DNAuthorization\ModuleOptions", $ModuleOptions);

      $AuthorizationUser = $this->serviceManager->get('DNAuthorization\AuthorizationUser');
      $this->assertInstanceOf("DNAuthorization\AuthorizationUser", $AuthorizationUser);
   }

}
