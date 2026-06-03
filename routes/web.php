<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\RoleMiddleware;

Router::get('/', 'HomeController@index');

Router::get('/catalogo', 'CatalogController@index');
Router::get('/ferramenta/{slug}', 'CatalogController@show');

Router::get('/sobre', 'PageController@about');
Router::get('/contato', 'PageController@contact');
Router::post('/contato', 'HomeController@contactSubmit');

Router::get('/login', 'AuthController@loginForm', ['GuestMiddleware']);
Router::post('/login', 'AuthController@login', ['GuestMiddleware']);
Router::get('/cadastro', 'AuthController@registerForm', ['GuestMiddleware']);
Router::post('/cadastro', 'AuthController@register', ['GuestMiddleware']);
Router::get('/logout', 'AuthController@logout');

Router::get('/esqueci-senha', 'AuthController@forgotForm', ['GuestMiddleware']);
Router::post('/esqueci-senha', 'AuthController@forgot', ['GuestMiddleware']);
Router::get('/redefinir-senha/{token}', 'AuthController@resetForm', ['GuestMiddleware']);
Router::post('/redefinir-senha/{token}', 'AuthController@reset', ['GuestMiddleware']);

Router::get('/auth/google', 'GoogleAuthController@redirectToGoogle', ['GuestMiddleware']);
Router::get('/auth/google/callback', 'GoogleAuthController@callback', ['GuestMiddleware']);

Router::get('/cliente/dashboard', 'ClientController@dashboard', ['AuthMiddleware']);
Router::get('/cliente/alugar/{id}', 'ClientController@rentForm', ['AuthMiddleware']);
Router::post('/cliente/alugar/{id}', 'ClientController@rent', ['AuthMiddleware']);
Router::get('/cliente/locacoes', 'ClientController@rentals', ['AuthMiddleware']);
Router::get('/cliente/locacoes/{id}', 'ClientController@rentalDetail', ['AuthMiddleware']);
Router::get('/cliente/perfil', 'ClientController@profile', ['AuthMiddleware']);
Router::post('/cliente/perfil', 'ClientController@updateProfile', ['AuthMiddleware']);

Router::get('/admin/dashboard', 'AdminController@dashboard', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/ferramentas', 'ToolController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/ferramentas/criar', 'ToolController@create', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/ferramentas', 'ToolController@store', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/ferramentas/{id}/editar', 'ToolController@edit', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/ferramentas/{id}', 'ToolController@update', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::delete('/admin/ferramentas/{id}', 'ToolController@destroy', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);

Router::get('/admin/categorias', 'CategoryController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/categorias/criar', 'CategoryController@create', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/categorias', 'CategoryController@store', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/categorias/{id}/editar', 'CategoryController@edit', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/categorias/{id}', 'CategoryController@update', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::delete('/admin/categorias/{id}', 'CategoryController@destroy', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);

Router::get('/admin/locacoes', 'RentalController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/locacoes/criar', 'RentalController@create', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/locacoes', 'RentalController@store', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::get('/admin/locacoes/{id}', 'RentalController@show', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/locacoes/{id}/devolver', 'RentalController@returnTool', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::post('/admin/locacoes/{id}/confirmar', 'RentalController@confirm', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);
Router::delete('/admin/locacoes/{id}/cancelar', 'RentalController@cancel', ['AuthMiddleware', ['RoleMiddleware', [['admin', 'staff']]]]);

Router::get('/admin/usuarios', 'UserController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);
Router::get('/admin/usuarios/{id}', 'UserController@show', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);
Router::post('/admin/usuarios/{id}/role', 'UserController@updateRole', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);
Router::post('/admin/usuarios/{id}/toggle', 'UserController@toggleActive', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);

Router::get('/admin/relatorios', 'ReportController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);
Router::get('/admin/configuracoes', 'SettingsController@index', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);
Router::post('/admin/configuracoes', 'SettingsController@update', ['AuthMiddleware', ['RoleMiddleware', [['admin']]]]);

Router::notFound(function () {
    abort(404);
});
