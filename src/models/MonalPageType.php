<?php
namespace Monal\Pages\Models;
/**
 * Page Type.
 *
 * The Monal System's implementation of the PageType model.
 *
 * @author	Arran Jacques
 */

use Monal\Data\Models\MonalDataStreamTemplate;
use Monal\Pages\Models\PageType;

class MonalPageType extends MonalDataStreamTemplate implements PageType
{
	/**
	 * The page type's template.
	 *
	 * @var		String
	 */
	protected $template = null;

	/**
	 * Return the page type's template.
	 *
	 * @return	String
	 */
	public function template()
	{
		return $this->template;
	}

	/**
	 * Set the page type's template.
	 *
	 * @param	String
	 * @return	Void
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * Generate a new page model based on the page type.
	 *
	 * @return	Monal\Pages\Models\Page
	 */
	public function newPageFromType()
	{
		$page = \PagesRepository::newModel();
		$page->setPageType($this);
		foreach ($this->dataSetTemplates() as $data_set_template) {
			$data_set = \DataSetsRepository::newModel($data_set_template);
			$data_set->setName($data_set_template->name());
			$page->addDataSet($data_set);
		}
		return $page;
	}

	/**
	 * Check the page type validates against a set of given rules.
	 *
	 * @param	Array
	 * @param	Array
	 * @return	Boolean
	 */
	public function validates(array $validation_rules = array(), array $validation_messages = array())
	{
		// Allow alpha, numeric, hypens, underscores and space characters, and must contain at least 1 alpha character.
		\Validator::extend('page_type_data_set_template_name', function($attribute, $value, $parameters)
		{
			return (preg_match('/^[a-z0-9 \-_]+$/i', $value) AND preg_match('/[a-zA-Z]/', $value)) ? true : false;
		});

		// Build the data to be validated.
		$data = array(
			'name' => $this->name,
			'table_prefix' => $this->table_prefix,
			'template' => $this->template
		);

		// Check the page validates.
		$page_type_validates = false;
		$validation = \Validator::make($data, $validation_rules, $validation_messages);
		if ($validation->passes()) {
			$page_type_validates = true;
		} else {
			$this->messages->merge($validation->messages());
		}

		// Check the data set templates validate.
		$templates_validate = true;
		$validation_rules = array(
			'name' => 'required|max:100|page_type_data_set_template_name',
			'component' => 'required|not_in:0',
		);
		$validation_messages = array(
			'name.required' => 'You need to give this data set a name.',
			'name.max' => 'The name for this data set is too long. It must be no more than 100 characters long.',
			'name.page_type_data_set_template_name' => 'The name for this data set is invalid. It can only contain letters, numbers, underscores, hyphens and spaces, and must contain at least 1 letter.',
			'component.required' => 'You need to set a component type for this data set.',
			'component.not_in' => 'You need to set a component type for this data set.',
		);
		$data_set_template_names = array();
		foreach ($this->data_set_templates as $data_set_template) {
			if (isset($data_set_template_names[\Str::slug($data_set_template->name())])) {
				$templates_validate = false;
				$this->messages->add('error', 'You can’t have two data sets with the same name.');
			}
			$data_set_template_names[\Str::slug($data_set_template->name())] = $data_set_template->name();
			if (!$data_set_template->validates($validation_rules, $validation_messages)) {
				$this->messages->add('error', 'There are some errors in the data sets you have used.');
				$templates_validate = false;
			}
		}

		return ($page_type_validates AND $templates_validate) ? true : false;
	}

	/**
	 * Return a GUI for the model.
	 *
	 * @param	Array
	 * @return	Illuminate\View\View
	 */
	public function view(array $settings = array())
	{
		$page_type = array(
			'name' => $this->name,
			'table_prefix' => $this->table_prefix,
			'template' => $this->template,
		);
		$templates = \Theme::templates();
		$data_set_temaplates = $this->data_set_templates;
		$show_validation = isset($settings['show_validation']) ? $settings['show_validation'] : false;
		$messages = $this->messages;
		return \View::make(
			'pages::models.page_type',
			compact(
				'messages',
				'page_type',
				'templates',
				'data_set_temaplates',
				'show_validation'
			)
		);
	}
}