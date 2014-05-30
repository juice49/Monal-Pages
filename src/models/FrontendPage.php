<?php
namespace Monal\Pages\Models;
/**
 * FrontendPage.
 *
 * A model of a Frontend page. This is a contract for implementations of this
 * model to follow.
 *
 * @author	Arran Jacques
 */

interface FrontendPage
{
	/**
	 * Return the pages's ID.
	 *
	 * @return	Integer
	 */
	public function ID();

	/**
	 * Return the pages's name.
	 *
	 * @return	String
	 */
	public function name();

	/**
	 * Return the pages's URL.
	 *
	 * @return	String
	 */
	public function URL();

	/**
	 * Return the page's data sets.
	 *
	 * @return	Array
	 */
	public function dataSets();

	/**
	 * Return the page's parent.
	 *
	 * @return	Monal\Pages\Models\FrontendPage
	 */
	public function parent();

	/**
	 * Return a collection of the page's child pages.
	 *
	 * @return	Illuminate\Database\Eloquent\Collection
	 */
	public function children();
}