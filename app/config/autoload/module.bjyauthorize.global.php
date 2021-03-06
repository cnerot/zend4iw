<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'bjyauthorize' => array(
        // default role for unauthenticated users
        'default_role' => 'guest',

        // identity provider service name
        'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',

        // Role providers to be used to load all available roles into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'role_providers' => array(
            'BjyAuthorize\Provider\Role\ZendDb' => array(
                'table' => 'user_role',
                'role_id_field' => 'role_id',
                'parent_role_field' => 'parent'
            )
        ),

        // Guard listeners to be attached to the application event manager
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcadmin', 'roles' => array('admin')),
                array('route' => 'zfcadmin/logout', 'roles' => array('admin')),
                array('route' => 'zfcadmin/login', 'roles' => array('guest', 'user', 'admin')),
                array('route' => 'zfcadmin/authenticate', 'roles' => array( 'guest', 'user', 'admin')),
                array('route' => 'zfcadmin/pokemon', 'roles' => array('admin')),
                array('route' => 'zfcadmin/pokemon/action', 'roles' => array('admin')),
                array('route' => 'home/action', 'roles' => array('guest', 'user', 'admin')),
                array('route' => '/', 'roles' => array('guest', 'user', 'admin')),
                array('route' => 'apipokemon', 'roles' => array('guest', 'user', 'admin')),
                array('route' => 'apipokemon/action', 'roles' => array('guest', 'user', 'admin'))
            )
        )
    )
);