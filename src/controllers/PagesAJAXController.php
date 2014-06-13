<?php
/**
 * Pages AJAX Controller.
 *
 * Controller for AJAX requests related to Page package. Acts as a
 * mediator between incoming AJAX requests and the application. 
 * Receives the AJAX requests and runs the appropriate application
 * layer logic, returning the results in an appropriate format/
 *
 * @author	Arran Jacques
 */

class PagesAJAXController extends BaseController
{
	/**
	 * Constructor.
	 *
	 * @return	Void
	 */
	public function __construct()
	{
		parent::__construct(\App::make('Monal\Monal'));
	}

	/**
	 * Order a collection of pages.
	 *
	 * @param	Array
	 * @return	JSON
	 */
	public function orderPages($data)
	{
		$output = array('status' => 'OK');
		try {
			PagesRepository::orderPages($data);
		} catch (Exception $e) {
			$output = array('status' => 'ERROR');
		}
		return json_encode($output, JSON_FORCE_OBJECT);
	}
}