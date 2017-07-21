<?php
namespace ApiPokemon;

return array(
    'ApiPokemon' => array(
    ),
    'router' => array(
        'routes' => array(
            'ApiPokemon' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/apipokemon',
                    'defaults' => array(
                        'module' => 'ApiPokemon',
                        'controller' => 'ApiPokemon\Controller\Pokemon',
                        'action' => 'get'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'action' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:action',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'ApiPokemon\Controller\Pokemon',
                                'action' => 'get'
                            )
                        ),
                        'may_terminate' => true
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
        'template_map' => array(
        ),

    )
);