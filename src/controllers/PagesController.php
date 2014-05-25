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
use Monal\Pages\Repositories\PagesRepository;
use Monal\Pages\Repositories\PageTypesRepository;

class PagesController extends AdminController
{
	/**
	 * The Pages repository. 
	 *
	 * @var		  Monal\Pages\Repositories\PagesRepository
	 */
	protected $pages_repo;

	/**
	 * The Page Types repository. 
	 *
	 * @var		  Monal\Pages\Repositories\PageTypesRepository
	 */
	protected $page_types_repo;

	/**
	 * Constructor.
	 *
	 * @param	Monal\GatewayInterface
	 * @param	Monal\Pages\Repositories\PagesRepository
	 * @param	Monal\Pages\Repositories\PageTypesRepository
	 * @return	Void
	 */
	public function __construct(
		GatewayInterface $system_gateway,
		PagesRepository $pages_repo,
		PageTypesRepository $page_types_repo
	) {
		parent::__construct($system_gateway);
		$this->pages_repo = $pages_repo;
		$this->page_types_repo = $page_types_repo;
	}

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
		$messages = $this->system->messages->get();
		return View::make('pages::pages.pages', compact('messages', 'pages'));
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
			$this->system->messages->add($validation->messages()->toArray());
		}
		$page_types = array(0 => 'Choose page type...');
		foreach ($this->page_types_repo->retrieve() as $page_type) {
			$page_types[$page_type->ID()] = $page_type->name();
		}
		$messages = $this->system->messages->get();
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
		if ($page_type = $this->page_types_repo->retrieve($id)) {
			$page = $page_type->newPageFromType();
			if ($this->input) {
				foreach (\DataSetsHelper::extractDataSetValuesFromInput($this->input) as $key => $data_set_values) {
					$page->dataSets()[$key]->setComponentValues($data_set_values['component_values']);
				}
				$page->setName(isset($this->input['name']) ? $this->input['name'] : null);
				$page->setSlug(isset($this->input['slug']) ? $this->input['slug'] : null);
				$page->setParent(isset($this->input['parent']) ? $this->input['parent'] : null);
				$page->setAsHomePage(isset($this->input['home_page']) ? true : false);
				$page->setTitle(isset($this->input['title']) ? $this->input['title'] : null);
				$page->setKeywords(isset($this->input['keywords']) ? $this->input['keywords'] : null);
				$page->setDescription(isset($this->input['description']) ? $this->input['description'] : null);
				if ($this->pages_repo->write($page)) {
					$this->system->messages->add(
						array(
							'success' => array(
								'You successfully created the page "' . $page->name() . '".',
							)
						)
					)->flash();
					return Redirect::route('admin.pages');
				}
			}
			foreach ($page->dataSets() as $data_set) {
				foreach ($data_set->componentCSS() as $css) {
					$this->system->dashboard->addCSS($css);
				}
				foreach ($data_set->componentScripts() as $script) {
					$this->system->dashboard->addScript($script);
				}
			}
			return View::make('pages::pages.create', compact('page', 'pages'));
		}
		return Redirect::route('admin.pages');
	}
}