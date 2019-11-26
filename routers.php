<?php
global $routes;
$routes = array();

// Criação de endpointer
$routes['/users/login']         = '/users/login';
$routes['/users/new']           = '/users/new_record';
$routes['/users/{id}']          = '/users/view/:id';
$routes['/users/{id}/feed']     = '/users/feed/:id';
$routes['/users/{id}/photos']   = '/users/phots/:id';
$routes['/users/{id}/follow']   = '/users/follow/:id';


$routes['/photos/random']       = '/photos/random';
$routes['/photos/new']          = '/photos/new_record';
$routes['/photos/{id}']         = '/photos/view/:id';
$routes['/photos/{id}/comment'] = '/photos/comment/:id';
$routes['/photos/{id}/like']    = '/photos/like/:id';


