<?php
namespace Monal\Pages\Models;
/**
 * Page Model.
 *
 * A model of a Page. This is a contract for implementations of this
 * model to follow.
 *
 * @author	Arran Jacques
 */

use Monal\Data\Models\DataStreamEntry;
use Monal\Data\Models\DataSet;

interface Page extends DataStreamEntry
{
	/**
	 * Return the pages's ID.
	 *
	 * @return	Integer
	 */
	public function ID();

	/**
	 * Return the model of the page type this page is implmenting.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function pageType();

	/**
	 * Return the pages's name.
	 *
	 * @return	String
	 */
	public function name();

	/**
	 * Return the ID page's parent.
	 *
	 * @return	Integer
	 */
	public function parent();

	/**
	 * Return the pages's slug.
	 *
	 * @return	String
	 */
	public function slug();

	/**
	 * Return the pages's URI.
	 *
	 * @return	String
	 */
	public function URI();

	/**
	 * Return the pages's title.
	 *
	 * @return	String
	 */
	public function title();

	/**
	 * Return the pages's keywords.
	 *
	 * @return	String
	 */
	public function keywords();

	/**
	 * Return the pages's description.
	 *
	 * @return	String
	 */
	public function description();

	/**
	 * Return the page's data sets.
	 *
	 * @return	Array
	 */
	public function dataSets();

	/**
	 * Is the page the applications home page?
	 *
	 * @return	Boolean
	 */
	public function isHomePage();

	/**
	 * Set the page's ID.
	 *
	 * @param	Integer
	 * @return	Void
	 */
	public function setID($id);

	/**
	 * Set the page type this page is implmenting.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Void
	 */
	public function setPageType(PageType $page_type);

	/**
	 * Set the page's name
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setName($name);

	/**
	 * Set the ID of the page's parent.
	 *
	 * @param	Monal\Pages\Models\MonalPage
	 * @return	Void
	 */
	public function setParent($parent);

	/**
	 * Set the page's slug
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setSlug($slug);

	/**
	 * Set the pages URI
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setURI($uri);

	/**
	 * Set the page's title
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setTitle($title);

	/**
	 * Set the page's keywords
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setKeywords($keywords);

	/**
	 * Set the page's description
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setDescription($description);

	/**
	 * Set if this page will act as the applications home page or not.
	 *
	 * @param	Boolean
	 * @return	Void
	 */
	public function setAsHomePage($is_home_page);

	/**
	 * Add a new data set to the page.
	 *
	 * @param	Monal\Data\Models\DataSet
	 * @return	Void
	 */
	public function addDataSet(DataSet $data_set);

	/**
	 * Check the page validates against a set of given rules.
	 *
	 * @param	Array
	 * @param	Array
	 * @return	Boolean
	 */
	public function validates(array $validation_rules = array(), array $validation_messages = array());

	/**
	 * Return a GUI for the model.
	 *
	 * @param	Array
	 * @return	Illuminate\View\View
	 */
	public function view(array $settings = array());
}