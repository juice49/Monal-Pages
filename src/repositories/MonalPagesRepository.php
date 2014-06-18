<?php
namespace Monal\Pages\Repositories;
/**
 * Monal Pages Repository.
 *
 * The Monal System's implementation of the PagesRepository.
 *
 * @author	Arran Jacques
 */

use Monal\Repositories\Repository;
use Monal\Pages\Repositories\PagesRepository;
use Monal\Pages\Models\Page;
use Monal\Pages\Models\PageType;

class MonalPagesRepository extends Repository implements PagesRepository
{
	/**
	 * The database table the repository uses.
	 *
	 * @var		String
	 */
	protected $table = 'pages';

	/**
	 * Return a new Page model.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function newModel()
	{
		return \App::make('Monal\Pages\Models\Page');
	}

	/**
	 * Query the repository.
	 *
	 * @return	Illuminate\Database\Query\Builder
	 */
	public function query()
	{
		return \DB::table($this->table);
	}

	/**
	 * Check a Page model validates for storage.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Boolean
	 */
	public function validatesForStorage(Page $page)
	{
		// Allow alpha, numeric, hypens, underscores, commas, ampersands,
		// apostrophes space characters, and must contain at least 1 alpha or
		// numeric character.
		\Validator::extend('page_name', function($attribute, $value, $parameters)
		{
			return (preg_match('/^[a-z0-9 \-_,\'&]+$/i', $value) AND preg_match('/[a-zA-Z0-9]/', $value)) ? true : false;
		});
		\Validator::extend('page_slug_chars', function($attribute, $value, $parameters)
		{
			return preg_match('/^[a-z0-9\-_\/]+$/i', $value) ? true : false;
		});
		\Validator::extend('page_slug_separators', function($attribute, $value, $parameters)
		{
			return (strpos($value, '//') !== false) ? false : true;
		});

		$unique_exception = ($page->ID()) ? ',' . $page->ID() : null;
		$validation_rules = array(
			'name' => 'required|max:100|page_name',
			'page_type' => 'required',
			'slug' => 'max:100|page_slug_chars|page_slug_separators',
			'url' => 'unique:pages,url' . $unique_exception,
			'title' => 'max:255'
		);
		$validation_messages = array(
			'name.required' => 'You need to give this page a Name.',
			'name.max' => 'Your Name for this page is to long. It must be no more than 100 characters long.',
			'name.page_name' => 'Your Name for this page is invalid. It can only contain letters, numbers, underscores, hyphens and spaces, and must contain at least 1 letter or number.',
			'page_type.required' => 'This page must implement a valid page type.',
			'slug.max' => 'Your Slug for this page is to long. It must be no more than 100 characters long.',
			'slug.page_slug_chars' => 'Your Slug for this page is invalid. It can only contain letters, numbers, underscores, hyphens and forward slashes.',
			'slug.page_slug_separators' => 'Your Slug for this page is invalid. Forward slashes must be separated by at least 1 valid character.',
			'url.unique' => 'There is already a page with this URL. Please change the page title or slug to ensure a unique URL.',
			'title.max' => 'Your Title for this page is to long. It must be no more than 255 characters long.',
		);
		$url = '/' . $page->slug();
		if ($page->parent()){
			if ($parent = $this->retrieve($page->parent()))
			{
				$url = '/' . trim($parent->url(), '/') . $url;
			}
		}
		$page->setURL($url);
		if ($page->validates($validation_rules, $validation_messages)) {
			return true;
		} else {
			$this->messages->merge($page->messages());
			return false;
		}
	}

	/**
	 * Encode a page model so it is ready to be stored in the repository.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Array
	 */
	protected function encodeForStorage(Page $page)
	{
		$page_type = ($page->pageType() instanceof PageType) ? $page->pageType()->ID() : null;
		return array(
			'name' => $page->name(),
			'page_type' => $page_type,
			'parent' => $page->parent(),
			'slug' => $page->slug(),
			'url' => $page->URL(),
			'is_home' => $page->isHomePage(),
			'title' => $page->title(),
			'keywords' => $page->keywords(),
			'description' => $page->description(),
		);
	}

	/**
	 * Decode a pages repository entry into its model class.
	 *
	 * @param	stdClass
	 * @return	Monal\Pages\Models\Page
	 */
	protected function decodeFromStorage($results)
	{
		if ($page_type = $page_type = \PageTypesRepository::retrieve($results->page_type)) {
			$page = $page_type->newPageFromType();
		} else {
			$page = $this->newModel();
		}
		$page->setID($results->id);
		$page->setName($results->name);
		$page->setParent($results->parent);
		$page->setSlug($results->slug);
		$page->setURL($results->url);
		$page->setAsHomePage($results->is_home);
		$page->setTitle($results->title);
		$page->setKeywords($results->keywords);
		$page->setDescription($results->description);
		$i = 0;
		$page_data_sets = \StreamSchema::getEntires($page_type, $results->id);
		if (!empty($page_data_sets)) {
			$page_data_sets = $page_data_sets[0];
			unset($page_data_sets->id);
			unset($page_data_sets->rel);
			unset($page_data_sets->created_at);
			unset($page_data_sets->updated_at);
			foreach ($page_data_sets as $value) {
				$page->dataSets()[$i]->component()->setValueFromStoragePreparedValues($value);
				$i++;
			}
		}
		return $page;
	}

	/**
	 * Return an array containing the details of all pages stored in the
	 * repository
	 *
	 * @return	Array
	 */
	public function getEntryLog()
	{
		$flat_page_map = array();
		foreach ($this->retrieve() as $page) {
			array_push($flat_page_map, array(
				'id' => $page->ID(),
				'page_type' => ($page->pageType() instanceof PageType) ? $page->pageType()->ID() : null,
				'name' => $page->name(),
				'parent' => $page->parent(),
				'slug' => $page->slug(),
				'url' => $page->URL(),
				'title' => $page->title(),
				'description' => $page->description(),
				'keywords' => $page->keywords(),
			));
		}
		return $flat_page_map;
	}

	/**
	 * Return an array summarising the details of each page and organised
	 * by page hierarchy.
	 *
	 * @return	Array
	 */
	public function getPagesTree()
	{
		$flat_page_map = array();
		foreach ($this->retrieve() as $page) {
			$flat_page_map[$page->ID()] = array(
				'page_type' => ($page->pageType() instanceof PageType) ? $page->pageType()->ID() : null,
				'name' => $page->name(),
				'parent' => $page->parent(),
				'slug' => $page->slug(),
				'url' => $page->URL(),
				'title' => $page->title(),
				'description' => $page->description(),
				'keywords' => $page->keywords(),
			);
		}
		return $this->buildPageHierarchy($flat_page_map);
	}

	/**
	 * Take a flat array of pages and organise them into a
	 * multidimensional array based on their  hierarchy.
	 *
	 * @param	Array
	 * @param	Integer
	 * @return	Array
	 */
	private function buildPageHierarchy($flat_page_map, $parent_page_id = null)
	{
		$tree_branch = array();
		foreach ($flat_page_map as $page_id => $page_details) {
			if (
				($parent_page_id === null AND $page_details['parent'] === 0) OR
				($parent_page_id AND $page_details['parent'] == $parent_page_id)
			) {
					array_push($tree_branch, array(
					'id' => $page_id,
					'page_type' => $page_details['page_type'],
					'name' => $page_details['name'],
					'parent' => $page_details['parent'],
					'slug' => $page_details['slug'],
					'url' => $page_details['url'],
					'title' => $page_details['title'],
					'description' => $page_details['description'],
					'keywords' => $page_details['keywords'],
					'children' => $this->buildPageHierarchy($flat_page_map, $page_id),
				));
			}
		}
		return $tree_branch;
	}

	/**
	 * Order a collection of pages in the repository.
	 *
	 * @param	Array
	 * @return	Void
	 */
	public function orderPages(array $page_order)
	{
		$page_orders = array();
		$page_ids = array();
		foreach ($page_order as $order => $page_id) {
			array_push($page_ids,  $page_id);
			$page_orders[$page_id] = $order;
		}
		$pages = \DB::table($this->table)->select('*')->whereIn('id', $page_ids)->get();
		if ($pages) {
			foreach ($pages as $page) {
				$page->order = $page_orders[$page->id];
				\DB::table($this->table)->where('id', '=', $page->id)->update((array) $page);
			}
		}
	}

	/**
	 * Retrieve an instance/s from the repository.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection / Monal\Pages\Models\Page
	 */
	public function retrieve($key = null)
	{
		$query = \DB::table($this->table)->select('*');
		if (!$key) {
			$results = $query->orderBy('order')->get();
			$pages = \App::make('Illuminate\Database\Eloquent\Collection');
			foreach ($results as &$result) {
				$pages->add($this->decodeFromStorage($result));
			}
			return $pages;
		} else {
			if ($result = $query->find($key)) {
				return $this->decodeFromStorage($result);
			}
		}
		return false;
	}

	/**
	 * Retrieve the home page from the repository.
	 *
	 * @return	Monal\Pages\Models\Page
	 */
	public function retrieveHomePage()
	{
		if ($page = $this->query()->where('is_home', '=', 1)->first()) {
			return $this->decodeFromStorage($page);
		}
		return false;
	}

	/**
	 * Retrieve a page from the repository by its URL.
	 *
	 * @param	String
	 * @return	Monal\Pages\Models\Page
	 */
	public function retrieveByURL($url)
	{
		if ($page = $this->query()->where('url', '=', $url)->first()) {
			return $this->decodeFromStorage($page);
		}
		return false;
	}

	/**
	 * Retrieve all child pages of a given page.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection
	 */
	public function retrieveChildren($id)
	{
		$pages = \App::make('Illuminate\Database\Eloquent\Collection');
		foreach ($this->query()->where('parent', '=', $id)->orderBy('order')->get() as $child) {
			$pages->add($this->decodeFromStorage($child));
		}
		return $pages;
	}

	/**
	 * Write a Pages model to the repository.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Boolean
	 */
	public function write(Page $page)
	{
		if ($this->validatesForStorage($page)) {
			$encoded = $this->encodeForStorage($page);
			if ($encoded['is_home']) {
				if ($existing_home_page = $this->query()->where('is_home', '=', 1)->first()) {
					$existing_home_page = $this->decodeFromStorage($existing_home_page);
					$existing_home_page->setAsHomePage(false);
					$this->write($existing_home_page);
				}
			}
			if ($page->ID()) {
				$encoded['updated_at'] = date('Y-m-d H:i:s');
				\DB::table($this->table)->where('id', '=', $page->ID())->update($encoded);
				\StreamSchema::updateEntry($page->page_type, $page);
				return true;
			} else {
				$encoded['created_at'] = date('Y-m-d H:i:s');
				$encoded['updated_at'] = date('Y-m-d H:i:s');
				$entry_id = \DB::table($this->table)->insertGetId($encoded);
				\StreamSchema::addEntry($page->page_type, $page, $entry_id);
				return true;
			}
		}
		return false;
	}
}