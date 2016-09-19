<?php

Config::set('site_name', 'InsaneNews');

Config::set('languages', array('ru', 'uk'));

//Routes. Route name => method prefix
Config::set('routes', array(
        'default' => '',
        'admin' => 'admin_'
));

Config::set('default_route', 'default');
Config::set('default_language', 'ru');
Config::set('default_controller', 'pages');
Config::set('default_action', 'index');

Config::set('db.host', 'mysql.zzz.com.ua');
Config::set('db.user', 'insaneuser');
Config::set('db.password', 'Module4newS');
Config::set('db.db_name', 'insanenews');

Config::set('salt', 'gjsdgf58dsfsdf26e9');
