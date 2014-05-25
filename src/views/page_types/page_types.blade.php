@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Page Types</h1>
@stop
@section('body-content')

	@if($system_user->hasAdminPermissions('page_types', 'create_page_type'))
		<div class="align--right">
			<a href="{{ URL::route('admin.page-types.create') }}" class="button button--wasabi">Create page type</a>
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
		<div class="wall__tiles">
			@foreach ($page_types as $page_type)
				<li class="tile">
					<div class="tile__content">
						<ul class="tile__properties">
							<li class="tile__property">
								<span class="tile__property__key">Name:</span>
								<span class="tile__property__value">{{ $page_type->name() }}</span>
							</li>
						</ul>
						<div class="node__y--top align--right">
							@if($system_user->hasAdminPermissions('page_types', 'edit_page_types'))
								<a href="{{ URL::route('admin.page-types.edit', $page_type->ID()) }}" class="button button--small button--dusk">Edit</a>
							@endif
							@if($system_user->hasAdminPermissions('page_types', 'delete_page_types'))
								<span class="button button--small button--cuban_heat">Delete</span>
							@endif
						</div>
					</div>
				</li>
			@endforeach
		</div>
	</div>

@stop