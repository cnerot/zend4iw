<?php
namespace Admin;

return array(
    'pokedex' => array(
    ),
    'zfcuser' => array(
        'enable_registration' => false,
        'auth_identity_fields' => array(
            'username'
        ),
        'use_redirect_parameter_if_present' => true,
        'login_redirect_route' => 'zfcadmin/pokemon',
        'logout_redirect_route' => 'zfcadmin'
    ),
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'login'
                            )
                        )
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'logout'
                            )
                        )
                    ),
                    'pokemon' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/{pokemon}',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Pokemon',
                                'action' => 'index',
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'action' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/:action[/:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]+'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Admin\Controller\Pokemon',
                                        'action' => 'index'
                                    )
                                ),
                                'may_terminate' => true
                            )
                        )
                    )
                )
            )
        )
    ),
    'navigation' => array(
        'admin' => array(
            'index' => array(
                'label' => 'Admin',
                'route' => 'zfcadmin',
                'pages' => array(
                    'pokemon' => array(
                        'label' => 'Pokemon',
                        'route' => 'zfcadmin/pokemon',
                        'pages' => array(
                            'new_pokemon' => array(
                                'label' => 'Nouveau pokemon',
                                'route' => 'zfcadmin/pokemon/action',
                                'params' => array(
                                    'action' => 'add'
                                )
                            ),
                            'edit_pokemon' => array(
                                'label' => 'Editer une fiche pokemon',
                                'route' => 'zfcadmin/pokemon/action',
                                'params' => array(
                                    'action' => 'edit'
                                )
                            ),
                            'get_pokemon' => array(
                                'label' => 'Fiche Pokemon',
                                'route' => 'zfcadmin/pokemon/action',
                                'params' => array(
                                    'action' => 'get'
                                )
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
        'template_map' => array(
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'admin_navigation' => 'ZfcAdmin\Navigation\Service\AdminNavigationFactory',
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    )
);
