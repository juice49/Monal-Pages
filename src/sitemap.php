<?php

// First pass of a sitemap integration for the Monal Pages module.
// Does not currently reflect nesting.

foreach(PagesRepository::retrieve() as $page) {

	$uri = $page->is_home ? '/' : $page->slug;

	Sitemap::register($uri, function($uri) use($page) {

		$pageEntity = App::make('SitemapEntity');

		$pageEntity->uri = $uri;
		$pageEntity->name = $page->name;

		return $pageEntity;

	});

}