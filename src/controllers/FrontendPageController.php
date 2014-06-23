<?php
/**
 * Frontend Pages Controller.
 *
 * Controller for HTTP/S requests to pages created via the Pages
 * package.
 *
 * @author	Arran Jacques
 */

use Monal\Pages\Models\Page;

class FrontendPagesController extends BaseController
{
	/**
	 * Controller for HTTP/S requests for a page created via the Pages
	 * package. Mediates the requests and outputs a response.
	 *
	 * @param	Monal\Pages\Models\Page
	 * @return	Illuminate\View\View
	 */
	public function page(Page $page)
	{
		// We can shorten this significantly if we remove the extension.
		// Todo: stop DB inserting extension
		
		$vars = array();
		$vars['messages'] = $this->system->messages->merge(FlashMessages::all());
		$vars['page'] = \App::make('Monal\Pages\Models\FrontendPage', $page);
		
		// For now, trim the extensions added to the db
		$theme = $page->pageType()->template();
		$theme = basename($theme, '.php');
		$theme = basename($theme, '.blade');
		
		return View::make('theme::' . $theme, $vars);
	}
}