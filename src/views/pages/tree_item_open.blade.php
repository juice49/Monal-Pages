<li class="pages__tree__branch">
	@if ($page['pseudo'])
		<div class="pages__tree__branch__details" data-pseudo="pseudo">
			<span class="pages__tree__order pages__tree__order--pseudo"></span>
			<span class="{{ $has_children ? 'page__tree__name--children ' : '' }}page__tree__name page__tree__name--pseudo">Pseudo page: /{{ $page['name'] }}</span>
	@else
		<div class="pages__tree__branch__details" data-pseudo="false" data-id="{{ $page['id'] }}">
			<span class="icon icon-table-small pages__tree__order"></span>
			<span class="{{ $has_children ? 'page__tree__name--children ' : '' }}page__tree__name">{{ $page['name'] }}</span>
			<div class="pages__tree__buttons">
				<a href="{{ URL::route('admin.pages.edit', $page['id']) }}" class="button button--small button--dusk">Edit</a>
				<span class="button button--small button--cuban_heat">Delete</span>
			</div>
	@endif
	</div>