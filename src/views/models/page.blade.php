<div class="js--well__settings">
	<div class="well">
		<div class="control_block">
			{{ Form::label('name', 'Name', array('class' => 'label label--block')) }}
			{{ Form::input('text', 'name', $page['name'], array('class' => 'js--name input__text')) }}
		</div>
		<div class="control_block">
			{{ Form::label('parent', 'Parent', array('class' => 'label label--block')) }}
			{{ Form::select('parent', $parent_pages, $page['parent'], array('class' => 'js--parent select')) }}
		</div>
		<div class="control_block">
			{{ Form::label('slug', 'Slug', array('class' => 'label label--block')) }}
			<label for="slug" class="label label--block label--description">By default the page's slug will be its name; however you can override this by specifying a custom slug below.</label>
			{{ Form::input('text', 'slug', $page['slug'], array('class' => 'js--slug input__text')) }}
		</div>
		<div class="control_block">
			{{ Form::label('url', 'URL', array('class' => 'label label--block')) }}
			{{ Form::input('text', 'url', null, array('class' => 'js--url input__text input__text--disabled')) }}
		</div>
		<div class="control_block">
			<label for="home_page" class="label checkbox">
				{{ Form::checkbox('home_page', 1, $page['is_home'], array('class' => 'checkbox__input', 'id' => 'home_page')) }}
				<span class="checkbox__label">Is home page</span>
			</label>
		</div>
	</div>

	@foreach ($data_sets as $data_set)
		{{ $data_set->view(array(
			'show_validation' => $show_validation
		)) }}
	@endforeach
</div>

<div class="js--well__meta">
	<div class="well">
		<div class="control_block">
			{{ Form::label('title', 'Title', array('class' => 'label label--block')) }}
			{{ Form::input('text', 'title', $page['title'], array('class' => 'input__text')) }}
		</div>
		<div class="control_block">
			{{ Form::label('keywords', 'Keywords', array('class' => 'label label--block')) }}
			<label for="keywords" class="label label--block label--description">Separate keywords using a comma.</label>
			{{ Form::input('text', 'keywords', $page['keywords'], array('class' => 'input__text')) }}
		</div>
		<div class="control_block">
			{{ Form::label('description', 'Description', array('class' => 'label label--block')) }}
			{{ Form::textarea('description', $page['description'], array('class' => 'textarea', 'rows' => '4')) }}
		</div>
	</div>
</div>

<script>
	(function(window, jQuery){

		'use strict';

		var parent_page_url_map = {}

		@foreach (PagesRepository::getEntryLog() as $page)
			parent_page_url_map['{{ $page['id'] }}'] = '{{ $page['url'] }}';
		@endforeach

		function pageSlug() {

			var
				name = $('.js--name').val(),
				slug = $('.js--slug').val(),
				parent_id = $('.js--parent').val(),
				parent_slug = '',
				page_slug;

			if (parent_id !== '0') {
				if (parent_page_url_map[parent_id] !== undefined) {
					parent_slug += parent_page_url_map[parent_id];
				}
			}

			parent_slug += '/';
			page_slug = (slug.length > 0) ? slug : slugify(name);

			$('.js--url').val(parent_slug + page_slug);
		}

		$(document).ready(function(){
			$('.js--name, .js--parent, .js--slug, .js--url').on('keyup', pageSlug).on('change', pageSlug);
			pageSlug();
		});
	})(window, jQuery);
</script>