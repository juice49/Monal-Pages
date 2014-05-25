@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Create Page</h1>
@stop
@section('body-content')

	<nav class="node__y--bottom navbar">
		<ul class="navbar__menu">
			<li class="navbar__menu__option"><span class="js--settings navbar__menu__link">Page Settings</span></li>
			<li class="navbar__menu__option"><span class="js--meta navbar__menu__link">Meta Data</span></li>
		</ul>
	</nav>

	{{ $page->view(array(
		'show_data_sets_validation' => true,
		'page_hierarchy' => PagesHelper::pagesHierarchyForSelect(),
		'save_button' => 'Create'
	)) }}

	<script>
		(function(window, jQuery){

			'use strict';

			$(document).ready(function(){
				$('.js--well__meta').hide();
				$('.js--settings').on('click', function(){
					$('.js--well__settings').show();
					$('.js--well__meta').hide();
				})
				$('.js--meta').on('click', function(){
					$('.js--well__settings').hide();
					$('.js--well__meta').show();
				})
			});
		})(window, jQuery);
	</script>
@stop