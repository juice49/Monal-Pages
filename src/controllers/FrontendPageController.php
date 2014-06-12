<?php
/**
 * Frontend Pages Controller.
 *
 * Controller for HTTP/S requests to pages created via the Pages
 * package.
 *
 * @author	Arran Jacques
 */

use Monal\GatewayInterface;
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
		$vars = array();
		$vars['messages'] = $this->system->messages->merge(FlashMessages::all());
		$vars['page'] = \App::make('Monal\Pages\Models\FrontendPage', $page);

		View::addLocation(Theme::path() . '/templates');
		View::addNamespace('theme', Theme::path() . '/templates');
		$theme = $page->pageType()->template();
		if (substr($theme, -10) == '.blade.php') {
			$theme = substr_replace($theme , '', -10);
		} else if (substr($theme, -4) == '.php') {
			$theme = substr_replace($theme , '', -4);
		}
		return View::make('theme::' . $theme, $vars);
	}
}