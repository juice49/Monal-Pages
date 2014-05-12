<?php

Monal::registerAdminRoute('any', 'page-types', 'admin.page-types', 'PageTypesController@pageTypes');
Monal::registerAdminRoute('any', 'page-types/create', 'admin.page-types.create', 'PageTypesController@create');
Monal::registerAdminRoute('any', 'page-types/edit/{id}', 'admin.page-types.edit', 'PageTypesController@edit');