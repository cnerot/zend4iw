<?php
namespace Pokedex;

return array(
    __NAMESPACE__ => array(
        'options' => array(
            'routes' => array(
                'backend' => 'zfcadmin',
                'backend-login' => 'zfcadmin/login',
                'frontend' => 'home',
                'frontend-login' => 'home/login',
                'api' => '/pokeapi/'
            )
        )
    ),
    'pokedex' => array(
    ),
    'bjyauthorize' => array(
        'unauthorized_strategy' => 'Pokedex\View\UnauthorizedStrategy'
    ),
    'service_manager' => array(
        'factories' => array(
            'BjyAuthorize\View\UnauthorizedStrategy' => 'Pokedex\View\UnauthorizedStrategy'
        )
    )
);
