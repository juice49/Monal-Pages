@extends('../dashboard')
@section('master-head')
	<script src="{{ URL::to('packages/monal/data/js/datasets.js') }}"></script>
	<script src="{{ URL::to('packages/monal/data/js/components.js') }}"></script>
@stop
@section('body-header')
	<h1 class="dashboard__title">Edit Page Type</h1>
@stop
@section('body-content')
	{{ $page_type->view(array(
		'show_data_sets_validation' => true,
		'save_button' => 'Update'
	)) }}
@stop