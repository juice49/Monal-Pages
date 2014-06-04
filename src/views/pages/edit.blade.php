@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Edit Page</h1>
@stop
@section('body-content')

	<nav class="node__y--bottom navbar">
		<ul class="navbar__menu">
			<li class="navbar__menu__option"><span class="js--settings navbar__menu__link">Page Settings</span></li>
			<li class="navbar__menu__option"><span class="js--meta navbar__menu__link">Meta Data</span></li>
		</ul>
	</nav>

	@if ($messages)
		<div class="node__y--bottom">
			<div class="message_box message_box--tomato">
				<span class="message_box__title">Great Scott!</span>
				<ul>
					@foreach($messages->all() as $message)
						<li>{{ $message }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif

	{{ Form::open() }}
		{{ $page->view(array(
			'show_validation' => true,
			'page_hierarchy' => PagesHelper::createPageListForSelect(),
		)) }}
		<div class="form__controls form__controls--standard control_block">
			<div class="form__controls__left">
				<a href="{{ URL::route('admin.pages') }}" class="button button--mustard">Cancel</a>
			</div>
			<div class="form__controls__right align--right">
				{{ Form::submit('Update', array('class' => 'button button--wasabi')) }}
			</div>
		</div>
	{{ Form::close() }}

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