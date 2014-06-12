<?php

Monal\API\Routes::addAdminRoute('any', 'pages', 'admin.pages', 'PagesController@pages');
Monal\API\Routes::addAdminRoute('any', 'pages/create/choose', 'admin.pages.create.choose', 'PagesController@choosePageType');
Monal\API\Routes::addAdminRoute('any', 'pages/create/{id}', 'admin.pages.create', 'PagesController@create');
Monal\API\Routes::addAdminRoute('any', 'pages/edit/{id}', 'admin.pages.edit', 'PagesController@edit');
Monal\API\Routes::addAdminRoute('any', 'page-types', 'admin.page-types', 'PageTypesController@pageTypes');
Monal\API\Routes::addAdminRoute('any', 'page-types/create', 'admin.page-types.create', 'PageTypesController@create');
Monal\API\Routes::addAdminRoute('any', 'page-types/edit/{id}', 'admin.page-types.edit', 'PageTypesController@edit');