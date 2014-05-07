<?php
/**
 * Page Type Controller.
 *
 * Controller for HTTP/S requests for the Page Type admin pages.
 *
 * @author	Arran Jacques
 */

use Monal\GatewayInterface;
use Monal\Pages\Repositories\PageTypesRepository;

class PageTypesController extends AdminController
{
	/**
	 * The page types repository. 
	 *
	 * @var		  Monal\Pages\Repositories\PageTypesRepository
	 */
	protected $page_types_repo;

	/**
	 * Constructor.
	 *
	 * @param	Monal\GatewayInterface
	 * @return	Void
	 */
	public function __construct(GatewayInterface $system_gateway, PageTypesRepository $page_types_repo) {
		parent::__construct($system_gateway);
		$this->page_types_repo = $page_types_repo;
	}

	/**
	 * Controller for HTTP/S requests for the Pages package’s page type
	 * admin page. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function pageTypes()
	{
		if (!$this->system->user->hasAdminPermissions('page_types')) {
			return Redirect::route('admin.dashboard');
		}
		$page_types = $this->page_types_repo->retrieve();
		$messages = $this->system->messages->get();
		return View::make('pages::page_types.page_types', compact('messages', 'page_types'));
	}

	/**
	 * Controller for HTTP/S requests for the Pages package’s create page
	 * type admin page. Mediates the requests and outputs a response.
	 *
	 * @return	Illuminate\View\View / Illuminate\Http\RedirectResponse
	 */
	public function create()
	{
		if (!$this->system->user->hasAdminPermissions('page_types', 'create_page_type')) {
			return Redirect::route('admin.pages');
		}
		$page_type = $this->page_types_repo->newModel();
		$page_type->setTablePrefix('page__');
		if ($this->input) {
			$page_type->setName(isset($this->input['name']) ? $this->input['name'] : null);
			$page_type->setTablePrefix(isset($this->input['table_prefix']) ? $this->input['table_prefix'] : null);
			$page_type->setTemplate(isset($this->input['template']) ? $this->input['template'] : null);
			$data_set_templates = \DataSetTemplatesHelper::extractDataSetTemplatesFromInput($this->input);
			foreach ($data_set_templates as $data_set_template) {
				$page_type->addDataSetTemplate($data_set_template);
			}
			if ($this->page_types_repo->validatesForStorage($page_type)) {
				if (\StreamSchema::build($page_type)) {
					$this->page_types_repo->write($page_type);
					$this->system->messages->add(
						array(
							'success' => array(
								'You successfully created the page type "' . $page_type->name() . '".',
							)
						)
					)->flash();
					return Redirect::route('admin.page-types');
				}
			}
			$this->system->messages->add($this->page_types_repo->messages()->toArray());
		}
		$templates = Theme::templates();
		$messages = $this->system->messages->get();
		return View::make('pages::page_types.create', compact('messages', 'templates', 'page_type'));
	}
}