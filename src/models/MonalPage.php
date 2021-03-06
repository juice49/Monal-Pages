<?php
namespace Monal\Pages\Models;
/**
 * Page.
 *
 * The Monal System's implementation of the Page model.
 *
 * @author	Arran Jacques
 */

use Monal\Models\Model;
use Monal\Pages\Models\Page;
use Monal\Data\Models\DataSet;

class MonalPage extends Model implements Page
{
	/**
	 * The page's ID.
	 *
	 * @var		Integer
	 */
	public $id = null;

	/**
	 * A model of the page type this page is implmenting.
	 *
	 * @var		Monal\Pages\Models\PageType
	 */
	public $page_type = null;

	/**
	 * The pages's name.
	 *
	 * @var		String
	 */
	public $name = null;

	/**
	 * The pages's parent.
	 *
	 * @var		Integer
	 */
	public $parent = null;

	/**
	 * The pages's slug.
	 *
	 * @var		String
	 */
	public $slug = null;

	/**
	 * The pages's URI.
	 *
	 * @var		String
	 */
	protected $uri = null;

	/**
	 * The pages's title.
	 *
	 * @var		String
	 */
	public $title = null;

	/**
	 * The pages's keywords.
	 *
	 * @var		String
	 */
	public $keywords = null;

	/**
	 * The pages's meta description.
	 *
	 * @var		String
	 */
	public $description = null;

	/**
	 * Is the page the application's home page.
	 *
	 * @var		Boolean
	 */
	public $is_home = false;

	/**
	 * An array of the page's data sets.
	 *
	 * @var		Array
	 */
	protected $data_sets = array();

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
	 * Return the model of the page type this page is implmenting.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function pageType()
	{
		return $this->page_type;
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
	 * Return the ID page's parent.
	 *
	 * @return	Integer
	 */
	public function parent()
	{
		return $this->parent;
	}

	/**
	 * Return the pages's slug.
	 *
	 * @return	String
	 */
	public function slug()
	{
		if (!$this->slug OR $this->slug == '') {
			return \Str::slug($this->name);
		}
		return strtolower($this->slug);
	}

	/**
	 * Return the pages's URI.
	 *
	 * @return	String
	 */
	public function URI()
	{
		return $this->uri;
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
	 * Return the pages's keywords.
	 *
	 * @return	String
	 */
	public function keywords()
	{
		return $this->keywords;
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
	 * Return the page's data sets.
	 *
	 * @return	Array
	 */
	public function dataSets()
	{
		return $this->data_sets;
	}

	/**
	 * Is the page the applications home page?
	 *
	 * @return	Boolean
	 */
	public function isHomePage()
	{
		return $this->is_home;
	}

	/**
	 * Set the page's ID.
	 *
	 * @param	Integer
	 * @return	Void
	 */
	public function setID($id)
	{
		$this->id = (integer) $id;
	}

	/**
	 * Set the page type this page is implmenting.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Void
	 */
	public function setPageType(PageType $page_type)
	{
		$this->page_type = $page_type;
	}

	/**
	 * Set the page's name
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Set the ID of the page's parent.
	 *
	 * @param	Monal\Pages\Models\MonalPage
	 * @return	Void
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * Set the page's slug
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setSlug($slug)
	{
		$this->slug = strtolower(trim($slug, '/'));
	}

	/**
	 * Set the pages URI
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setURI($uri)
	{
		$uri = strtolower(trim($uri, '/'));
		$this->uri = '/' . $uri;
	}

	/**
	 * Set the page's title
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Set the page's keywords
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
	}

	/**
	 * Set the page's description
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Set if this page will act as the applications home page or not.
	 *
	 * @param	Boolean
	 * @return	Void
	 */
	public function setAsHomePage($is_home_page)
	{
		$this->is_home = $is_home_page;
	}

	/**
	 * Add a new data set to the page.
	 *
	 * @param	Monal\Data\Models\DataSet
	 * @return	Void
	 */
	public function addDataSet(DataSet $data_set)
	{
		array_push($this->data_sets, $data_set);
	}

	/**
	 * Return an array that summarises each data that makes up the page.
	 *
	 * @return	Array
	 */
	public function reduceDataSets()
	{
		$data_sets = \App::make('Illuminate\Database\Eloquent\Collection');
        foreach ($this->data_sets as $data_set) {
            $data_sets->put($data_set->name(), $data_set->component()->reduceValues()); 
        }
        return $data_sets;
	}

	/**
	 * Check the page validates against a set of given rules.
	 *
	 * @param	Array
	 * @param	Array
	 * @return	Boolean
	 */
	public function validates(array $validation_rules = array(), array $validation_messages = array())
	{
		$page_validates = false;
		$data_sets_validate = true;
		$data = array(
			'name' => $this->name,
			'page_type' => $this->page_type,
			'slug' => $this->slug,
			'uri' => $this->URI(),
			'title' => $this->title,
			'keywords' => $this->keywords,
			'description' => $this->description,
		);
		$validation = \Validator::make($data, $validation_rules, $validation_messages);
		if ($validation->passes()) {
			$page_validates = true;
		} else {
			$this->messages->merge($validation->messages());
		}

		foreach ($this->dataSets() as $data_set) {
			if (!$data_set->validates()) {
				$data_sets_validate = false;
				$this->messages->add('error', 'The values you have entered below contain some errors. Please check them.');
			}
		}

		return ($page_validates AND $data_sets_validate) ? true : false;
	}

	/**
	 * Return a GUI for the model.
	 *
	 * @param	Array
	 * @return	Illuminate\View\View
	 */
	public function view(array $settings = array())
	{
		$page = array(
			'name' => $this->name,
			'parent' => $this->parent,
			'slug' => $this->slug,
			'is_home' => $this->is_home,
			'title' => $this->title,
			'keywords' => $this->keywords,
			'description' => $this->description,
		);
		$data_sets = $this->data_sets;
		$page_hierarchy = isset($settings['page_hierarchy']) ? $settings['page_hierarchy'] : array();
		$parent_pages = array('0' => 'None') + $page_hierarchy;
		$show_validation = isset($settings['show_validation']) ? $settings['show_validation'] : false;
		$messages = $this->messages;
		return \View::make(
			'pages::models.page',
			compact(
				'messages',
				'page',
				'parent_pages',
				'data_sets',
				'show_validation'
			)
		);
	}
}