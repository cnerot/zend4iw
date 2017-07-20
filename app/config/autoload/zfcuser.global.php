<?php
$settings = array(
    'zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
    'user_entity_class' => 'ZfcUser\Entity\User',
    'enable_registration' => false,
    'enable_username' => true,
    'auth_adapters' => array( 100 => 'ZfcUser\Authentication\Adapter\Db' ),
    'enable_display_name' => true,
    'auth_identity_fields' => array('email', 'username'),
    'login_form_timeout' => 300,
    'user_form_timeout' => 600,
    'login_after_registration' => false,
    'use_registration_form_captcha' => false,
    'use_redirect_parameter_if_present' => true,
    'table_name' => 'user',
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcuser' => $settings,
    'service_manager' => array(
        'aliases' => array(
            'zfcuser_zend_db_adapter' => (isset($settings['zend_db_adapter'])) ? $settings['zend_db_adapter']: 'Zend\Db\Adapter\Adapter',
        ),
    ),
);
