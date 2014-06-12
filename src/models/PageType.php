<?php
namespace Monal\Pages\Models;
/**
 * Page Type Model.
 *
 * A model for a Page Type. This is a contract for implementations of
 * this model to follow.
 *
 * @author	Arran Jacques
 */

use Monal\Data\Models\DataStreamTemplate;

interface PageType extends DataStreamTemplate
{
	/**
	 * Return the page type's template.
	 *
	 * @return	String
	 */
	public function template();

	/**
	 * Set the page type's template.
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setTemplate($template);

	/**
	 * Generate a new page model based on the page type.
	 *
	 * @return	Monal\Pages\Models\Page
	 */
	public function newPageFromType();

	/**
	 * Check the page type validates against a set of given rules.
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