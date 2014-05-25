<?php
namespace Monal\Pages\Libraries;
/**
 * Pages Helper.
 *
 * A helper library for working with Pages and Page Types.
 *
 * @author	Arran Jacques
 */

class PagesHelper
{
	/**
	 * The Pages repository. 
	 *
	 * @var		  Monal\Pages\Repositories\PageRepository
	 */
	protected $pages_repo = null;

	/**
	 * Constructor.
	 *
	 * @return	Void
	 */
	public function __construct()
	{
		$this->pages_repo = \App::make('Monal\Pages\Repositories\PagesRepository');
	}

	/**
	 * Return a flat array containing summaries of each page.
	 *
	 * @return	Void
	 */
	public function pagesSummary()
	{
		$flat_page_map = array();
		foreach ($this->pages_repo->retrieve() as $page) {
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
	 * Return a flat array of pages the depicts page hierarchy and can be
	 * used to build form selects to choose parent pages.
	 *
	 * @return	Array
	 */
	public function pagesHierarchyForSelect()
	{
		$select_array = array();
		foreach ($this->pages_repo->pageHierarchySummary() as $page) {
			$select_array[$page['id']] =  $page['name'];
			if (!empty($page['children'])) {
				$select_array = $select_array + $this->recurseIntoChildren($page['children'], 1);
			}
		}
		return $select_array;
	}

	/**
	 * Recurse into a pages children to help build a flat array of pages
	 * the depicts page hierarchy and can be used to build form selects to
	 * choose parent pages.
	 *
	 * @param	Array
	 * @param	Integer
	 * @return	Void
	 */
	private function recurseIntoChildren($children, $recursion_lvl)
	{
		$select_array = array();
		$pre_append = '';
		for ($i = 0; $i < $recursion_lvl; $i++){
			$pre_append .= '-';
		}
		foreach ($children as $page) {
			$select_array[$page['id']] = $pre_append . $page['name'];
			if (!empty($page['children'])) {
				$select_array = $select_array + $this->recurseIntoChildren($page['children'], ($recursion_lvl + 1));
			}
		}
		return $select_array;
	}
}