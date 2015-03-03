<?php

namespace DNAuthorizationTest\Module;

use PHPUnit_Framework_TestCase as PHPUnit;

/**
 * Description of AuthorizationTest
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class AuthorizationTest extends PHPUnit {

   private $serviceManager;

   protected function setUp() {
      $this->serviceManager = \Bootstrap::getServiceManager();
   }

   public function testConfig() {
      $config = $this->serviceManager->get('Config');
      $this->assertTrue(isset($config["dn-authorization"]));
   }

}
