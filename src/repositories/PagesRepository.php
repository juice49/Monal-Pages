<?php
namespace Monal\Pages\Repositories;
/**
 * Pages Repository.
 *
 * A repository for storing Pages. This is a contract for
 * implementations of this repository to follow. The class defines
 * methods for reading, writing, updating and removing Pages to the
 * repository.
 *
 * @author	Arran Jacques
 */

use Monal\Pages\Models\Page;

interface PagesRepository
{
	/**
	 * Return the repository's messages.
	 *
	 * @return	Illuminate\Support\MessageBag
	 */
	public function messages();

	/**
	 * Return a new Page model.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function newModel();

	/**
	 * Check a Page model validates for storage.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Boolean
	 */
	public function validatesForStorage(Page $page);

	/**
	 * Retrieve an instance/s from the repository.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection / Monal\Pages\Models\Page
	 */
	public function retrieve($key = null);

	/**
	 * Return an array summarising the details of each page and organised
	 * by page hierarchy.
	 *
	 * @return	Array
	 */
	public function pageHierarchySummary();

	/**
	 * Write a Pages model to the repository.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Boolean
	 */
	public function write(Page $page);
}