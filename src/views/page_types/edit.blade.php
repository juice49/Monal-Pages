@extends('../dashboard')
@section('body-header')
	<h1 class="dashboard__title">Edit Page Type</h1>
@stop
@section('body-content')
	{{ $page_type->view(array(
		'show_data_sets_validation' => true,
		'save_button' => 'Update'
	)) }}
@stop