<?php
namespace Monal\Pages\Libraries;
/**
 * Pages Helper.
 *
 * A helper library for working with Pages and Page Types.
 *
 * @author	Arran Jacques
 */

use Monal\Pages\Models\Page;

class PagesHelper
{
	/**
	 * Create the dashboard view that hierarchically lists the pages
	 * created via the Pages package.
	 *
	 * @return	Illuminate\View\View
	 */
	public function createPagesTreeHTML()
	{
		$pages = array();
		foreach (\PagesRepository::retrieve() as $page) {
			$page_details = array(
				'id' => $page->ID(),
				'name' => $page->name(),
				'uri_segments' => explode('/', trim($page->URI(), '/')),
			);
			array_push($pages, $page_details);
		}
		return $this->recursivelyBuildHTML($pages);
	}

	/**
	 * Recurse into the page hierarchy created by the
	 * recursivelyBuildPageTree function and create a HTML list of pages.
	 *
	 * @param	Array
	 * @param	Boolean
	 * @return	String
	 */
	private function recursivelyBuildHTML($pages, $recurse = false)
	{
		$html = \View::make('pages::pages.tree_open', compact('page'))->render();
		$pages_tree = (!$recurse) ? $this->recursivelyBuildPageTree($pages) : $pages;
		foreach ($pages_tree as $key => $page) {
			$has_children = (!empty($page['children'])) ? true : false;
			$html .= \View::make('pages::pages.tree_item_open', compact('page', 'has_children'))->render();
			if ($has_children) {
				$html .= $this->recursivelyBuildHTML($page['children'], true);
			}
			$html .= \View::make('pages::pages.tree_item_close', compact('page'))->render();
		}
		$html .= \View::make('pages::pages.tree_close', compact('page'))->render();
		return $html;
	}

	/**
	 * Recurse into the page map created in the createPagesTreeHTML function
	 * and build a hierarchical array of pages.
	 *
	 * @param	Array
	 * @param	String
	 * @param	Integer
	 * @return	Array
	 */
	private function recursivelyBuildPageTree($pages, $parent_slug = null, $recursion_lvl = 0)
	{
		$tree = array();
		$pseudo_namespaces = array();
		foreach ($pages as $page) {
			if (is_null($parent_slug)) {
				$slug = $page['uri_segments'][$recursion_lvl];
				$pseudo = $this->isPseudoSlug($slug, $recursion_lvl, $pages);
				if (count($page['uri_segments']) == 1) {
					array_push($tree, array(
						'pseudo' => 0,
						'id' => $page['id'],
						'name' => $page['name'],
						'children' => $this->recursivelyBuildPageTree($pages, $slug, ($recursion_lvl + 1)),
					));
				} else {
					if ($pseudo AND !isset($pseudo_namespaces[$slug])) {
						$pseudo_namespaces[$slug] = $slug;
						array_push($tree, array(
							'pseudo' => 1,
							'name' => $slug,
							'children' => $this->recursivelyBuildPageTree($pages, $slug, ($recursion_lvl + 1)),
						));
					}
				}
			} else {
				if (count($page['uri_segments']) == ($recursion_lvl + 1)) {
					$slug = $page['uri_segments'][$recursion_lvl];
					if ($page['uri_segments'][$recursion_lvl - 1] == $parent_slug) {
						if ($pseudo = $this->isPseudoSlug($slug, $recursion_lvl, $pages)) {
							$pseudo_namespaces[$slug] = $slug;
							array_push($tree, array(
								'pseudo' => 1,
								'name' => $slug,
								'children' => $this->recursivelyBuildPageTree($pages, $slug, ($recursion_lvl + 1)),
							));
						} else {
							array_push($tree, array(
								'pseudo' => 0,
								'id' => $page['id'],
								'name' => $page['name'],
								'children' => $this->recursivelyBuildPageTree($pages, $slug, ($recursion_lvl + 1)),
							));
						}
					}
				}
			}
		}
		return $tree;
	}

	/**
	 * Check if a slug corresponds to a real page or if it represents a
	 * pseudo page created by specifying a custom slug.
	 *
	 * @return	Array
	 */
	private function isPseudoSlug($slug, $segment, $pages)
	{
		foreach ($pages as $page) {
			if (count($page['uri_segments']) == ($segment + 1) AND $slug == $page['uri_segments'][$segment]) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Return a flat array of pages the depicts page hierarchy and can be
	 * used to build form selects.
	 *
	 * @return	Array
	 */
	public function createPageListForSelect()
	{
		$select_array = array();
		foreach (\PagesRepository::getPagesTree() as $page) {
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