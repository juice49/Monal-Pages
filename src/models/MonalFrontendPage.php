<?php
namespace Monal\Pages\Models;
/**
 * Frontend Page.
 *
 * The Monal System's implementation of the FrontendPage model.
 *
 * @author	Arran Jacques
 */

use Monal\Pages\Models\Page;

class MonalFrontendPage implements FrontendPage
{
	/**
	 * The Pages repository. 
	 *
	 * @var		Monal\Pages\Repositories\PagesRepository
	 */
	protected $pages_repo = null;

	/**
	 * The page's ID.
	 *
	 * @var		Integer
	 */
	public $id = null;

	/**
	 * The page's name.
	 *
	 * @var		String
	 */
	public $name = null;

	/**
	 * The page's URL.
	 *
	 * @var		String
	 */
	public $url = null;

	/**
	 * An array of the page's data sets.
	 *
	 * @var		Array
	 */
	public $data_sets = null;

	/**
	 * The ID of the pages parent.
	 *
	 * @var		Integer
	 */
	private $parent_id = null;

	/**
	 * The page's parent.
	 *
	 * @var		Monal\Pages\Models\Page
	 */
	protected $parent = null;

	/**
	 * A collection of the page's children.
	 *
	 * @var		Illuminate\Database\Eloquent\Collection
	 */
	protected $children = null;

	/**
	 * Constructor.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Void
	 */
	public function __construct(Page $page)
	{
		$this->pages_repo = \App::make('Monal\Pages\Repositories\PagesRepository');

		$this->id = $page->ID();
		$this->name = $page->name();
		$this->url = \URL::to($page->URL());
		$this->parent_id = $page->parent();
		$this->data_sets = new \stdClass;

		$pages_data_sets = $page->dataSets();
		if (!empty($pages_data_sets)) {
			$components = \App::make('Monal\Data\Libraries\ComponentsInterface');
			foreach ($page->dataSets() as $data_set) {
				$stripped_values = $components->make($data_set->componentURI())
					->stripImplementationValues($data_set->componentValues());
				$this->data_sets->{\Text::snakeCaseString($data_set->name())} = $stripped_values;
			}
		}
	}

	/**
	 * Return the pages's ID.
	 *
	 * @return	Integer
	 */
	public function ID()
	{
		return $this->id;
	}

	/**
	 * Return the pages's name.
	 *
	 * @return	String
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * Return the pages's URL.
	 *
	 * @return	String
	 */
	public function URL()
	{
		return $this->url;
	}

	/**
	 * Return the page's data sets.
	 *
	 * @return	Array
	 */
	public function dataSets()
	{
		return $this->data_sets;
	}

	/**
	 * Return the page's parent.
	 *
	 * @return	Monal\Pages\Models\FrontendPage
	 */
	public function parent()
	{
		if ($this->parent_id) {
			if (is_null($this->parent)) {
				$parent = $this->pages_repo->retrieve($this->parent_id);
				$this->parent = \App::make('Monal\Pages\Models\FrontendPage', $parent);
			}
		}
		return $this->parent;
	}

	/**
	 * Return a collection of the page's child pages.
	 *
	 * @return	Illuminate\Database\Eloquent\Collection
	 */
	public function children()
	{
		if (is_null($this->children)) {
			$this->children = \App::make('Illuminate\Database\Eloquent\Collection');
			foreach ($this->pages_repo->retrieveChildren($this->ID) as $child) {
				$this->children->add(\App::make('Monal\Pages\Models\FrontendPage', $child));
			}
		}
		return $this->children;
	}
}
