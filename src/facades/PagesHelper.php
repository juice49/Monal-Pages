<?php
namespace Monal\Pages\Facades;

use Illuminate\Support\Facades\Facade;

class PagesHelper extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return	String
	 */
	protected static function getFacadeAccessor() { return 'pageshelper'; }
}