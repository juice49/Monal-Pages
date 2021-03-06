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
	 * Return a new Page model.
	 *
	 * @return	Monal\Pages\Models\Page
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
	 * Return an array containing the details of all pages stored in the
	 * repository
	 *
	 * @return	Array
	 */
	public function getEntryLog();

	/**
	 * Return an array summarising the details of each page and organised
	 * by page hierarchy.
	 *
	 * @return	Array
	 */
	public function getPagesTree();

	/**
	 * Order a collection of pages in the repository.
	 *
	 * @param	Array
	 * @return	Void
	 */
	public function orderPages(array $page_order);

	/**
	 * Retrieve an instance/s from the repository.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection / Monal\Pages\Models\Page
	 */
	public function retrieve($key = null);

	/**
	 * Retrieve the home page from the repository.
	 *
	 * @return	Monal\Pages\Models\Page
	 */
	public function retrieveHomePage();

	/**
	 * Retrieve a page from the repository by its URI.
	 *
	 * @param	String
	 * @return	Monal\Pages\Models\Page
	 */
	public function retrieveByURI($uri);

	/**
	 * Retrieve all child pages of a given page.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection
	 */
	public function retrieveChildren($id);

	/**
	 * Write a Pages model to the repository.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Boolean
	 */
	public function write(Page $page);
}