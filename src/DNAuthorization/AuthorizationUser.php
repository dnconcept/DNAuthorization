<?php

namespace DNAuthorization;

use DNAuthorization\Exception\AuthorizationException;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\Acl;

/**
 * Description of AuthorizationUser
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class AuthorizationUser implements AuthorizationUserInterface {

   private $defaultRole = "guest";

   /** @var AuthenticationServiceInterface */
   private $authService;

   /** @var Acl */
   private $accessControlList;

   /**
    * 
    * @param AuthenticationServiceInterface $authService
    * @param Acl $acl
    */
   public function __construct(AuthenticationServiceInterface $authService, Acl $acl) {
      $this->authService = $authService;
      $this->accessControlList = $acl;
   }

   public function getDefaultRole() {
      return $this->defaultRole;
   }

   public function setDefaultRole($defaultRole) {
      if (!$this->accessControlList->hasRole($defaultRole)) {
         throw new AuthorizationException("The access control list must define the default role : $defaultRole");
      }
      $this->defaultRole = $defaultRole;
      return $this;
   }

   /**
    * Retourne le rôle de l'utilistateur à partir du service d'authentification
    * @return string
    */
   public function getUserRole() {
      $identity = $this->authService->getIdentity();
      $role = $this->getDefaultRole();
      if (is_object($identity)) {
         if (!method_exists($identity, "getRole")) {
            throw new AuthorizationException("You must specify a getRole method for the identity object");
         }
         $identityRole = $identity->getRole();
         if ($this->accessControlList->hasRole($identityRole)) {
            $role = $identityRole;
         }
      }
      return $role;
   }

}
