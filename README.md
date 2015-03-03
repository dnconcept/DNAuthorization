# DNAuthorization
Zf2 Module for Authorization

This module provides an easy way to deal with authorization

On routing, the module will check if the active role is allowed to access the controller and action

```php
$this->acl->isAllowed($role, $controller, $action)
```

The controller is the resource, action is privilege

##Installation

add "DNAuthorization" to your application.config.php

```php
return [
  'module' => [
    ...
    "DNAuthorization"
    ...
  ]
]
```

##Configuration
The autoload configuration file looks like this

```php
<?php

return [
    'dn-authorization' => [
        //The route to redirect on if the user is not authorised
        "redirect_route" => "user/login",
        //Whether to redirect unauthorized person to redirect route or not
        "redirect_unauthorized" => true,
        //The default role if none is provided by the idendity of Zend\Authentication\AuthenticationService
        "default_role" => "guest",
        //Exclusion for authorization
        "exclusions" => [
            "controllers" => [
                "Application\Controller\Projets"
            ],
            "routes" => [
                "admin"
            ],
            "modules" => [
                "ModuleName"
            ]
        ]
    ],
];
```

