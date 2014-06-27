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
	 * The page's slug.
	 *
	 * @var		String
	 */
	public $slug = null;

	/**
	 * The page's URI.
	 *
	 * @var		String
	 */
	protected $uri = null;

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
	protected $parent_id = null;

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
	 * The page's title.
	 *
	 * @var		String
	 */
	public $title = null;

	/**
	 * The page's description.
	 *
	 * @var		String
	 */
	public $description = null;

	/**
	 * The page's keywords.
	 *
	 * @var		String
	 */
	public $keywords = null;

	/**
	 * Is the page the home page?
	 *
	 * @var		Boolean
	 */
	protected $home_page = null;

	/**
	 * Constructor.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Void
	 */
	public function __construct(Page $page)
	{
		$this->id = $page->ID();
		$this->name = $page->name();
		$this->slug = $page->slug();
		$this->uri = $page->URI();
		$this->parent_id = $page->parent();
		$this->data_sets = new \stdClass;
		$this->title = $page->title();
		$this->description = $page->description();
		$this->keywords = $page->keywords();
		$this->home_page = $page->isHomePage();

		$pages_data_sets = $page->dataSets();
		if (!empty($pages_data_sets)) {
			foreach ($pages_data_sets as $data_set) {
				$value = $data_set->component()->prepareValuesForOutput();
				$this->data_sets->{\Text::snakeCaseString($data_set->name())} = $value;
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
	 * Return the pages's slug.
	 *
	 * @return	String
	 */
	public function slug()
	{
		return $this->slug;
	}

	/**
	 * Return the pages's uri.
	 *
	 * @return	String
	 */
	public function URI()
	{
		return $this->uri;
	}

	/**
	 * Return the pages's URL.
	 *
	 * @return	String
	 */
	public function URL()
	{
		return $this->isHomePage() ? \URL::to('/') : \URL::to($this->uri);
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
				$parent = \PagesRepository::retrieve($this->parent_id);
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
			foreach (\PagesRepository::retrieveChildren($this->id) as $child) {
				$this->children->add(\App::make('Monal\Pages\Models\FrontendPage', $child));
			}
		}
		return $this->children;
	}

	/**
	 * Return the pages's title.
	 *
	 * @return	String
	 */
	public function title()
	{
		return $this->title;
	}

	/**
	 * Return the pages's description.
	 *
	 * @return	String
	 */
	public function description()
	{
		return $this->description;
	}

	/**
	 * Return the pages's keywords.
	 *
	 * @return	String
	 */
	public function keywords()
	{
		return $this->keywords;
	}

	/**
	 * Return a meta title for the page.
	 *
	 * @return	String
	 */
	public function metaTitle()
	{
		return ($this->title == '') ? $this->name : $this->title;
	}

	/**
	 * Return a meta tag for the page’s description.
	 *
	 * @return	String
	 */
	public function metaDescriptionTag()
	{
		return '<meta name="description" content="' . $this->description() . '" />';
	}

	/**
	 * Return a meta tag for the page’s keywords.
	 *
	 * @return	String
	 */
	public function metaKeywordsTag()
	{
		return '<meta name="keywords" content="' . $this->keywords() . '" />';
	}

	/**
	 * Is this page the home page?
	 *
	 * @return	Boolean
	 */
	public function isHomePage()
	{
		return $this->home_page;
	}

	/**
	 * Return a canonical link for the page.
	 *
	 * @return	String
	 */
	public function canonicalLink()
	{
		return $this->URL();
	}

	/**
	 * Return a canonical tag for the page.
	 *
	 * @return	String
	 */
	public function canonicalTag()
	{
		return '<link rel="canonical" href="' . $this->canonicalLink() . '" />';
	}
}
