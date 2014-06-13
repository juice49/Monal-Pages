<?php
namespace Monal\Pages\Repositories;
/**
 * Monal Page Types Repository.
 *
 * The Monal System's implementation of the PageTypesRepository.
 *
 * @author	Arran Jacques
 */

use Monal\Repositories\Repository;
use Monal\Pages\Repositories\PageTypesRepository;
use Monal\Pages\Models\PageType;

class MonalPageTypesRepository extends Repository implements PageTypesRepository
{
	/**
	 * The database table the repository uses.
	 *
	 * @var		String
	 */
	protected $table = 'page_types';

	/**
	 * Return a new Page Type model.
	 *
	 * @return	Monal\Pages\Models\PageType
	 */
	public function newModel()
	{
		return \App::make('Monal\Pages\Models\PageType');
	}

	/**
	 * Check a Page Type model validates for storage.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Boolean
	 */
	public function validatesForStorage(PageType $page_type)
	{
		// Allow alpha, numeric, hypens, underscores and space characters, and must contain at least 1 alpha character.
		\Validator::extend('page_type_name', function($attribute, $value, $parameters)
		{
			return (preg_match('/^[a-z0-9 \-_]+$/i', $value) AND preg_match('/[a-zA-Z]/', $value)) ? true : false;
		});
		// Allow alpha, hypens and underscores, and must contain at least 1 alpha character.
		\Validator::extend('table_prefix', function($attribute, $value, $parameters)
		{
			return (preg_match('/^[a-z\-_]+$/i', $value) AND preg_match('/[a-zA-Z]/', $value)) ? true : false;
		});

		$unique_exception = ($page_type->ID()) ? ',' . $page_type->ID() : null;
		$validation_rules = array(
			'name' => 'required|max:100|page_type_name|unique:page_types,name' . $unique_exception,
			'table_prefix' => 'table_prefix',
			'template' => 'required|max:100',
		);
		$validation_messages = array(
			'name.required' => 'You need to give this page type a Name.',
			'name.max' => 'Your Name for this page type is too long. It must be no more than 100 characters long.',
			'name.page_type_name' => 'Your Name for this page type is invalid. It can only contain letters, numbers, underscores, hyphens and spaces, and must contain at least 1 letter.',
			'name.unique' => 'There is already a page type using this Name. Please choose a different one',
			'table_prefix.table_prefix' => 'Your Table Prefix for this page type is invalid. It can only contain letters, underscores and hypens, and must contain at least one letter.',
			'template.required' => 'You need to set a Template for this page type.',
			'template.max' => 'Your name for the Template for this page type is too long. It must be no more than 100 characters long.',
		);
		if ($page_type->validates($validation_rules, $validation_messages)) {
			return true;
		} else {
			$this->messages->merge($page_type->messages());
			return false;
		}
	}

	/**
	 * Encode a Page Type model so it is ready to be stored in the
	 * repository.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Array
	 */
	protected function encodeForStorage(PageType $page_type)
	{
		$encoded = array(
			'name' => $page_type->name(),
			'table_prefix' => $page_type->tablePrefix(),
			'template' => $page_type->template(),
			'data_set_templates' => array(),
		);
		foreach ($page_type->dataSetTemplates() as $data_set_template) {
			array_push(
				$encoded['data_set_templates'],
				array(
					'uri' => $data_set_template->URI(),
					'name' => $data_set_template->name(),
					'component' => $data_set_template->component()->URI(),
					'component_settings' => $data_set_template->component()->settings(),
				)
			);
		}
		$encoded['data_set_templates'] = json_encode($encoded['data_set_templates'], JSON_FORCE_OBJECT);
		return $encoded;
	}

	/**
	 * Decode a Page Type repository entry into its model class.
	 *
	 * @param	stdClass
	 * @return	Monal\Pages\Models\PageType
	 */
	protected function decodeFromStorage($results)
	{
		$page_type = $this->newModel();
		$page_type->setID($results->id);
		$page_type->setName($results->name);
		$page_type->setTablePrefix($results->table_prefix);
		$page_type->setTemplate($results->template);
		$data_set_templates = json_decode($results->data_set_templates, true);
		foreach ($data_set_templates as $encoded_data_set_template) {
			$data_set_template = \DataSetTemplatesRepository::newModel();
			$data_set_template->setURI($encoded_data_set_template['uri']);
			$data_set_template->setName($encoded_data_set_template['name']);
			$component = \Components::makeTemplate($encoded_data_set_template['component']);
			$component->setSettings($encoded_data_set_template['component_settings']);
			$data_set_template->setComponent($component);
			$page_type->addDataSetTemplate($data_set_template);
		}
		return $page_type;
	}

	/**
	 * Retrieve an instance/s from the repository.
	 *
	 * @param	Integer
	 * @return	Illuminate\Database\Eloquent\Collection / Monal\Pages\Models\PageType
	 */
	public function retrieve($key = null)
	{
		$query = \DB::table($this->table)->select('*');
		if (!$key) {
			$results = $query->orderBy('name')->get();
			$page_types = \App::make('Illuminate\Database\Eloquent\Collection');
			foreach ($results as &$result) {
				$page_types->add($this->decodeFromStorage($result));
			}
			return $page_types;
		} else {
			if ($result = $query->find($key)) {
				return $this->decodeFromStorage($result);
			}
		}
		return false;
	}

	/**
	 * Write a Page Type model to the repository.
	 *
	 * @param	Monal\Pages\Models\PageType
	 * @return	Boolean
	 */
	public function write(PageType $page_type)
	{
		if ($this->validatesForStorage($page_type)) {
			$encoded = $this->encodeForStorage($page_type);
			if ($page_type->ID()) {
				$encoded['updated_at'] = date('Y-m-d H:i:s');
				\StreamSchema::update($this->retrieve($page_type->ID()), $page_type);
				\DB::table($this->table)->where('id', '=', $page_type->ID())->update($encoded);
				return true;
			} else {
				$encoded['created_at'] = date('Y-m-d H:i:s');
				$encoded['updated_at'] = date('Y-m-d H:i:s');
				\StreamSchema::build($page_type);
				\DB::table($this->table)->insert($encoded);
				return true;
			}
		}
		return false;
	}
}