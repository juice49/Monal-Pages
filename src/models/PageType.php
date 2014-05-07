<?php
namespace Monal\Pages\Models;
/**
 * Page Type Model
 *
 * A model for a Page Type. This is a contract for implementations of
 * this model to follow. The model defines the ... for the Page Type.
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
}