<?php

Monal::registerAdminRoute('any', 'pages', 'admin.pages', 'PagesController@pages');
Monal::registerAdminRoute('any', 'pages/create/choose', 'admin.pages.create.choose', 'PagesController@choosePageType');
Monal::registerAdminRoute('any', 'pages/create/{id}', 'admin.pages.create', 'PagesController@create');
Monal::registerAdminRoute('any', 'page-types', 'admin.page-types', 'PageTypesController@pageTypes');
Monal::registerAdminRoute('any', 'page-types/create', 'admin.page-types.create', 'PageTypesController@create');
Monal::registerAdminRoute('any', 'page-types/edit/{id}', 'admin.page-types.edit', 'PageTypesController@edit');