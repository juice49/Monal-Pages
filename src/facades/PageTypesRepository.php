<?php
namespace Monal\Pages\Facades;

use Illuminate\Support\Facades\Facade;

class PageTypesRepository extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return	String
	 */
	protected static function getFacadeAccessor() { return 'pagetypesrepository'; }
}