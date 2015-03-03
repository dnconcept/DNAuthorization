<?php

namespace DNAuthorization;

/**
 * Description of ModuleOptions
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class ModuleOptions extends \Zend\Stdlib\AbstractOptions {

   /** @var string      The route to redirect on if the user is not authorised */
   private $redirectRoute = "home";

   /** @var string      The default role for user */
   private $defaultRole = "guest";

   /** @var boolean     Whether to redirect unauthorized person to redirect route or not */
   private $redirectUnauthorized = true;

   /** @var array       Array of objects to exclude if match  */
   private $exclusions;

   public function getRedirectRoute() {
      return $this->redirectRoute;
   }

   public function getRedirectUnauthorized() {
      return $this->redirectUnauthorized;
   }

   public function getExclusions() {
      return $this->exclusions;
   }

   public function setRedirectRoute($redirectRoute) {
      $this->redirectRoute = $redirectRoute;
      return $this;
   }

   public function setRedirectUnauthorized($redirectUnauthorized) {
      $this->redirectUnauthorized = $redirectUnauthorized;
      return $this;
   }

   public function setExclusions($exclusions) {
      $this->exclusions = $exclusions;
      return $this;
   }

   public function getDefaultRole() {
      return $this->defaultRole;
   }

   public function setDefaultRole($defaultRole) {
      $this->defaultRole = $defaultRole;
      return $this;
   }

}
