@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Pages</h1>
@stop
@section('body-content')

	@if($system_user->hasAdminPermissions('pages', 'create_page'))
		<div class="align--right">
			<a href="{{ URL::route('admin.pages.create.choose') }}" class="button button--wasabi">Create page</a>
		</div>
	@endif

	@if ($messages)
		<div class="node__y--top">
			@if ($messages->has('success'))
				<div class="message_box message_box--wasabi">
					<span class="message_box__title">Woot!</span>
					<ul>
						@foreach($messages->all() as $message)
							<li>{{ $message }}</li>
						@endforeach
					</ul>
				</div>
			@else
				<div class="message_box message_box--tomato">
					<span class="message_box__title">Great Scott!</span>
					<ul>
						@foreach($messages->all() as $message)
							<li>{{ $message }}</li>
						@endforeach
					</ul>
				</div> 
			@endif
		</div>
	@endif

	<div class="node__y--top">
		{{ $pages_tree }}
	</div>

@stop