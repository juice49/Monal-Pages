<?php
/**
 * Pages Controller.
 *
 * Controller for HTTP/S requests for the Pages pacakge's admin
 * pages.
 *
 * @author	Arran Jacques
 */

use Monal\GatewayInterface;

class PagesController extends AdminController
{

	/**
	 * Controller for HTTP/S requests for the Pages page of the Pages
	 * package. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function pages()
	{
		if (!$this->system->user->hasAdminPermissions('pages')) {
			return Redirect::route('admin.dashboard');
		}
		$pages_tree = PagesHelper::createPagesTreeHTML();
		$messages = $this->system->messages->merge(FlashMessages::all());
		$this->system->dashboard->addCSS('packages/monal/pages/css/pages.css');
		$this->system->dashboard->addScript('packages/monal/pages/js/pages.js');
		return View::make('pages::pages.pages', compact('messages', 'pages_tree'));
	}

	/**
	 * Controller for HTTP/S requests for the Choose Page Type page of the
	 * Pages package. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function choosePageType()
	{
		if (!$this->system->user->hasAdminPermissions('pages', 'create_page')) {
			return Redirect::route('admin.pages');
		}
		if ($this->input) {
			$validation = Validator::make(
				$this->input,
				array(
					'page_type' => 'required|not_in:0',
				),
				array(
					'page_type.required' => 'You need to choose a page type to use for your new page.',
					'page_type.not_in' => 'You need to choose a page type to use for your new page.',
				)
			);
			if ($validation->passes()) {
				return Redirect::route('admin.pages.create', $this->input['page_type']);
			}
			$this->system->messages->merge($validation->messages());
		}
		$page_types = array(0 => 'Choose page type...');
		foreach (PageTypesRepository::retrieve() as $page_type) {
			$page_types[$page_type->ID()] = $page_type->name();
		}
		$messages = $this->system->messages->merge(FlashMessages::all());
		return View::make('pages::pages.choose', compact('messages', 'page_types'));
	}

	/**
	 * Controller for HTTP/S requests for the Create Page page of the
	 * Pages package. Mediates the requests and outputs a response.
	 *
	 * @param	Integer
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function create($id)
	{
		if (!$this->system->user->hasAdminPermissions('pages', 'create_page_type')) {
			return Redirect::route('admin.pages');
		}
		if ($page_type = PageTypesRepository::retrieve($id)) {
			$page = $page_type->newPageFromType();
			if ($this->input) {
				foreach (\DataSetsHelper::extractDataSetValuesFromInput($this->input) as $key => $data_set_values) {
					$page->dataSets()[$key]->component()->setValues($data_set_values['component_values']);
				}
				$page->setName(isset($this->input['name']) ? $this->input['name'] : null);
				$page->setSlug(isset($this->input['slug']) ? $this->input['slug'] : null);
				$page->setParent(isset($this->input['parent']) ? $this->input['parent'] : null);
				$page->setAsHomePage(isset($this->input['home_page']) ? true : false);
				$page->setTitle(isset($this->input['title']) ? $this->input['title'] : null);
				$page->setKeywords(isset($this->input['keywords']) ? $this->input['keywords'] : null);
				$page->setDescription(isset($this->input['description']) ? $this->input['description'] : null);
				if (PagesRepository::write($page)) {
					FlashMessages::flash('success', 'You successfully created the page "' . $page->name() . '".');
					return Redirect::route('admin.pages');
				}
				$messages = $this->system->messages->merge(PagesRepository::messages());
			}
			foreach ($page->dataSets() as $data_set) {
				foreach ($data_set->component()->css() as $css) {
					$this->system->dashboard->addCSS($css);
				}
				foreach ($data_set->component()->scripts() as $script) {
					$this->system->dashboard->addScript($script);
				}
			}
			$messages = $this->system->messages->merge(FlashMessages::all());
			return View::make('pages::pages.create', compact('messages', 'page', 'pages'));
		}
		return Redirect::route('admin.pages');
	}

	/**
	 * Controller for HTTP/S requests for the Edit Page page of the
	 * Pages package. Mediates the requests and outputs a response.
	 *
	 * @param	Integer
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function edit($id)
	{
		if (!$this->system->user->hasAdminPermissions('pages', 'edit_page_type')) {
			return Redirect::route('admin.pages');
		}
		if ($page = PagesRepository::retrieve($id)) {
			if ($this->input) {
				foreach (\DataSetsHelper::extractDataSetValuesFromInput($this->input) as $key => $data_set_values) {
					$page->dataSets()[$key]->component()->setValues($data_set_values['component_values']);
				}
				$page->setName(isset($this->input['name']) ? $this->input['name'] : null);
				$page->setSlug(isset($this->input['slug']) ? $this->input['slug'] : null);
				$page->setParent(isset($this->input['parent']) ? $this->input['parent'] : null);
				$page->setAsHomePage(isset($this->input['home_page']) ? true : false);
				$page->setTitle(isset($this->input['title']) ? $this->input['title'] : null);
				$page->setKeywords(isset($this->input['keywords']) ? $this->input['keywords'] : null);
				$page->setDescription(isset($this->input['description']) ? $this->input['description'] : null);
				if (PagesRepository::write($page)) {
					FlashMessages::flash('success', 'You successfully updated the page "' . $page->name() . '".');
					return Redirect::route('admin.pages');
				}
				$messages = $this->system->messages->merge(PagesRepository::messages());
			}
			foreach ($page->dataSets() as $data_set) {
				foreach ($data_set->component()->css() as $css) {
					$this->system->dashboard->addCSS($css);
				}
				foreach ($data_set->component()->scripts() as $script) {
					$this->system->dashboard->addScript($script);
				}
			}
			$messages = $this->system->messages->merge(FlashMessages::all());
			return View::make('pages::pages.edit', compact('messages', 'page'));
		}
		return Redirect::route('admin.pages');
	}
}