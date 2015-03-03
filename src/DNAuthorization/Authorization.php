<?php

namespace DNAuthorization;

use DNAuthorization\Exception\AuthorizationException;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;
use Zend\View\Model\ViewModel;

/**
 * Description of Authorization
 *
 * @author Nicolas Desprez <contact@dnconcept.fr>
 */
class Authorization {

   const ACL_INCORRECT = "ACL_INCORRECT";
   const ACL_UNAUTHORISED = "ACL_UNAUTHORISED";

   private $messages = [
       self::ACL_INCORRECT => "La liste des contrôles d'accés est incorrect",
       self::ACL_UNAUTHORISED => "Vous n'êtes pas autorisés dans cette zone",
   ];

   /** @var Acl */
   private $acl;

   /** @var AuthorizationUserInterface */
   private $service;

   public function __construct(Acl $acl, AuthorizationUserInterface $service) {
      $this->acl = $acl;
      $this->service = $service;
   }

   /**
    * @return ModuleOptions
    */
   private function getOptions($serviceLocator) {
      return $serviceLocator->get("DNAuthorization\ModuleOptions");
   }

   public function onDispatchError(MvcEvent $event) {
      $error = $event->getError();
      $errors = array_keys($this->messages);
      if (empty($error) || !in_array($error, $errors)) {
         return;
      }
      $result = $event->getResult();
      if ($result instanceof StdResponse) {
         return;
      }

      $baseModel = new ViewModel();
      $baseModel->setTemplate('layout/layout');

      $model = new ViewModel();

      $baseModel->addChild($model);
      $baseModel->setTerminal(true);

      $model->setVariable("message", "DNAuthorizationModule : " . $this->messages[$error]);
      $model->setVariable("exception", $event->getParam("exception", null));
      $model->setVariable("display_not_found_reason", true);
      $model->setVariable("display_exceptions", true);

      $event->setViewModel($baseModel);

      $response = $event->getResponse();

      switch ($error) {
         case self::ACL_INCORRECT :
            $model->setTemplate('error/404');
            $response->setStatusCode(404);
            break;
         case self::ACL_UNAUTHORISED :
            $model->setTemplate('error/403');
            $response->setStatusCode(403);
            break;
         default:
            $response->setStatusCode(500);
            break;
      }

      $event->setResponse($response);
      $event->setResult($baseModel);
      return false;
   }

   /**
    * @var Acl $acl
    * @param MvcEvent $event
    * @return boolean
    * @throws AuthorizationException
    */
   public function checkAuthorization(MvcEvent $event) {
      
      $app = $event->getTarget();
      $sm = $app->getServiceManager();
      $options = $this->getOptions($sm);

      $routeMatch = $event->getRouteMatch();
      $controller = $routeMatch->getParam('controller');
      $module = explode("\\", $controller)[0];
      if ($this->isExclude($options->getExclusions(), $controller, $routeMatch->getMatchedRouteName(), $module)) {
         return true;
      }
      try {
         $action = $routeMatch->getParam('action');
         $role = $this->service->getUserRole();
         if (!$this->acl->isAllowed($role, $controller, $action)) {
            return $this->setUnauthorized($event);
         }
      } catch (InvalidArgumentException $exc) {
//         throw $exc;
         //Une erreure s'est produite avec la méthode isAllowed : La resource n'existe pas
         $event->setError(self::ACL_INCORRECT)
                 ->setParam('route', $routeMatch->getMatchedRouteName())
                 ->setParam("exception", $exc);
         $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
         return false;
      }
   }

   /**
    * Permet de gérer le cas où l'utilisateur n'est pas autorisé
    * @param MvcEvent $event
    * @return boolean|Response   Renvoie une Response dans le cas de la redirection 
    */
   private function setUnauthorized(MvcEvent $event) {
      //L'utilisateur n'est pas autorisé
      $app = $event->getTarget();
      $event->stopPropagation();
      $options = $this->getOptions($app->getServiceManager());
      //On déclenche l'événement EVENT_DISPATCH_ERROR par défaut
      $event->setError(self::ACL_UNAUTHORISED);
      if ($options->getRedirectUnauthorized()) {
         //On le redirige vers la page d'accueil si l'option est définie
         return $this->redirectToHome($event, $options->getRedirectRoute());
      }
      $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
      return false;
   }

   /**
    * Permet d'exclure l'authorization pour des controllers, routes ou modules contenu dans le tableau d'exclusion
    * @param array $exclusions   Tableau contenant 3 clés (controllers, routes, modules) 
    * @param string $controller  Controller
    * @param string $routeName   Nom de la route
    * @param string $module      Nom du module ou namespace
    * @return boolean
    */
   private function isExclude($exclusions, $controller, $routeName, $module) {
      if (isset($exclusions["controllers"]) && in_array($controller, $exclusions["controllers"])) {
         return true;
      }
      if (isset($exclusions["routes"]) && in_array($routeName, $exclusions["routes"])) {
         return true;
      }
      if (isset($exclusions["modules"]) && in_array($module, $exclusions["modules"])) {
         return true;
      }
      return false;
   }

   /**
    * Redirige la réponse à la route home
    * @param MvcEvent $event
    * @param string $route
    * @return Response
    */
   private function redirectToHome(MvcEvent $event, $route) {
      $router = $event->getRouter();
      $url = $router->assemble(array(), array('name' => $route));
      $response = $event->getResponse();
      $response->getHeaders()->clearHeaders()->addHeaderLine('Location', $url);
      $response->setStatusCode(Response::STATUS_CODE_302);
      return $response;
//      $response->sendHeaders();
//      $event->setResponse($response);
//      $app = $event->getTarget();
//      $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
//      exit;
   }

}
