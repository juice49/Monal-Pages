@extends('../dashboard')
@section('body-header')
	<h1 class="color--teal">Page Types</h1>
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
					<h6>Woot!</h6>
					<ul>
						@foreach($messages->all() as $message)
							<li>{{ $message }}</li>
						@endforeach
					</ul>
				</div>
			@else
				<div class="message_box message_box--tomato">
					<h6>Great Scott!</h6>
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
				<div class="tile">
					<div class="tile__content">
						<table class="tile__table">
							<tbody>
								<tr>
									<td><span class="tile__table--row_title">Name:</span></td>
									<td>{{ $page_type->name() }}</td>
								</tr>
							</tbody>
						</table>
						<div class="node__y--top align--right">
							@if($system_user->hasAdminPermissions('page_types', 'edit_page_types'))
								<a href="{{ URL::route('admin.page-types', $page_type->ID()) }}" class="button button--small button--dusk">Edit</a>
							@endif
							@if($system_user->hasAdminPermissions('page_types', 'delete_page_types'))
								<span class="button button--small button--cuban_heat">Delete</span>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>

@stop