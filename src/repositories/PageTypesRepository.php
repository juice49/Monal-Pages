<?php
namespace Monal\Pages\Repositories;
/**
 * Page Types Repository.
 *
 * A repository for storing Page Types. This is a contract for
 * implementations of this repository to follow. The class defines
 * methods for reading, writing, updating and removing Page Types to
 * the repository.
 *
 * @author	Arran Jacques
 */

use Monal\Pages\Models\PageType;

interface PageTypesRepository
{
	/**
	 * Return a new Page Type model.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function newModel();

	/**
	 * Check a Page Type model validates for storage.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Boolean
	 */
	public function validatesForStorage(PageType $page_type);

	/**
	 * Retrieve an instance/s from the repository.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection / Monal\Pages\Models\PageType
	 */
	public function retrieve($key = null);

	/**
	 * Write a Page Type model to the repository.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Boolean
	 */
	public function write(PageType $page_type);
}