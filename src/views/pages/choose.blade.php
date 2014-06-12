@extends('../dashboard')
@section('body-header')
	<h2 class="dashboard__subtitle">Create Page</h2>
	<h1 class="dashboard__title">Choose Page Type</h1>
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
		<div class="well">
			@if (count($page_types) == 1)
				<div class="message_box message_box--mustard">
					<ul>
						<span class="message_box__title">Hey There!</span>
						<li>New pages are created by implementing page types. Before you can create a new page you first need to have created a page type.</li>
					</ul>
				</div>
			@endif
			<div class="control_block">
				{{ Form::label('page_type', 'Use Page Type', array('class' => 'label label--block')) }}
				{{ Form::select('page_type', $page_types, Input::has('page_type') ? Input::get('page_type') : null, array('class' => 'select')) }}
			</div>
		</div>
		<div class="form__controls form__controls--standard control_block">
			<div class="form__controls__left">
				<a href="{{ URL::route('admin.pages') }}" class="button button--mustard">Cancel</a>
			</div>
			<div class="form__controls__right align--right">
				{{ Form::submit('Use page type', array('class' => 'button button--wasabi')) }}
			</div>
		</div>
	{{ Form::close() }}

@stop