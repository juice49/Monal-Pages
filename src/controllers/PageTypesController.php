<?php
/**
 * Page Types Controller.
 *
 * Controller for HTTP/S requests for the Pages pacakge's admin
 * pages.
 *
 * @author	Arran Jacques
 */

class PageTypesController extends AdminController
{
	/**
	 * Controller for HTTP/S requests for the Page Types page of the Pages
	 * package. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function pageTypes()
	{
		if (!$this->system->user->hasAdminPermissions('page_types')) {
			return Redirect::route('admin.dashboard');
		}
		$page_types = PageTypesRepository::retrieve();
		$messages = $this->system->messages->merge(FlashMessages::all());
		return View::make('pages::page_types.page_types', compact('messages', 'page_types'));
	}

	/**
	 * Controller for HTTP/S requests for the Create Page Type page of the
	 * Pages package. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function create()
	{
		if (!$this->system->user->hasAdminPermissions('page_types', 'create_page_type')) {
			return Redirect::route('admin.pages');
		}
		$page_type = PageTypesRepository::newModel();
		$page_type->setTablePrefix('page__');
		if ($this->input) {
			$page_type->setName(isset($this->input['name']) ? $this->input['name'] : null);
			$page_type->setTablePrefix(isset($this->input['table_prefix']) ? $this->input['table_prefix'] : null);
			$page_type->setTemplate(isset($this->input['template']) ? $this->input['template'] : null);
			$data_set_templates = \DataSetTemplatesHelper::extractDataSetTemplatesFromInput($this->input);
			foreach ($data_set_templates as $data_set_template) {
				$page_type->addDataSetTemplate($data_set_template);
			}
			if (PageTypesRepository::write($page_type)) {
				FlashMessages::flash('success', 'You successfully created the page type "' . $page_type->name() . '".');
				return Redirect::route('admin.page-types');
			}
			$messages = $this->system->messages->merge(PageTypesRepository::messages());
		}
		$this->system->dashboard->addScript('packages/monal/data/js/datasets.js');
		$this->system->dashboard->addScript('packages/monal/data/js/components.js');
		$messages = $this->system->messages->merge(FlashMessages::all());
		return View::make('pages::page_types.create', compact('messages', 'page_type'));
	}

	/**
	 * Controller for HTTP/S requests for the Edit Page Type page of the
	 * Pages package. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function edit($id)
	{
		if (!$this->system->user->hasAdminPermissions('page_types', 'edit_page_type')) {
			return Redirect::route('admin.page-types');
		}
		if ($page_type = PageTypesRepository::retrieve($id)) {
			if ($this->input) {
				$page_type->setName(isset($this->input['name']) ? $this->input['name'] : null);
				$page_type->setTablePrefix(isset($this->input['table_prefix']) ? $this->input['table_prefix'] : null);
				$page_type->discardDataSetTemplates();
				$data_set_templates = \DataSetTemplatesHelper::extractDataSetTemplatesFromInput($this->input);
				foreach ($data_set_templates as $data_set_template) {
					$page_type->addDataSetTemplate($data_set_template);
				}
				if (PageTypesRepository::write($page_type)) {
					$this->system->messages->add('success', 'You successfully updated the page type "' . $page_type->name() . '".');
					return Redirect::route('admin.page-types');
				}
				$messages = $this->system->messages->merge(PageTypesRepository::messages());
			}
			$this->system->dashboard->addScript('packages/monal/data/js/datasets.js');
			$this->system->dashboard->addScript('packages/monal/data/js/components.js');
			$messages = $this->system->messages->merge(FlashMessages::all());
			return View::make('pages::page_types.edit', compact('messages', 'page_type'));
		}
		return Redirect::route('admin.page-types');
	}
}