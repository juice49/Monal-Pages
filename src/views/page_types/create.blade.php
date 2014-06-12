@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Create Page Type</h1>
@stop
@section('body-content')

	@if ($messages->any())
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
		{{ $page_type->view(array(
			'show_validation' => true
		)) }}
		<div class="form__controls form__controls--standard control_block">
			<div class="form__controls__left">
				<a href="{{ URL::route('admin.page-types') }}" class="button button--mustard">Cancel</a>
			</div>
			<div class="form__controls__right align--right">
				{{ Form::submit('Create', array('class' => 'button button--wasabi')) }}
			</div>
		</div>
	{{ Form::close() }}
@stop