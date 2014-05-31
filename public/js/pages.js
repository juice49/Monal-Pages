(function(window, $){

	window.openPageTree = function(opt) {
		var option = $(opt);
		option.parent('.pages__tree__branch__details')
			.next('.pages__tree')
			.slideDown(200);
		option.data('state', 'open')
			.addClass('page__tree__name--open');
	};

	window.closePageTree = function(opt) {
		var option = $(opt);
		option.parent('.pages__tree__branch__details')
			.next('.pages__tree')
			.slideUp(200);
		option.data('state', 'closed')
			.removeClass('page__tree__name--open');
		option.parent('.pages__tree__branch__details')
			.next('.pages__tree')
			.find('.pages__tree')
			.slideUp(200);
		option.parent('.pages__tree__branch__details')
			.next('.pages__tree')
			.find('.page__tree__name--children')
			.data('state', 'closed')
			.removeClass('page__tree__name--open');
	};

	$(document).ready(function(){

		$('.pages__tree').find('.pages__tree').hide();
		$('.page__tree__name--children').on('click', function(){
			if ($(this).data('state') == 'open') {
				closePageTree(this);
			} else {
				openPageTree(this);
			}
		});

		$('.pages__tree__order').on('mousedown', function(){
			closePageTree($(this).next('.page__tree__name--children'));
		});

		$('.pages__tree').sortable({
			items: '.pages__tree__branch',
			handle: '.pages__tree__order',
			stop: function(e, ui) {
				var data = {
					_use: 'PagesAJAXController@orderPages'
				}
				$(this).children('.pages__tree__branch').children('.pages__tree__branch__details').each(function(i) {
					var $t = $(this);
					if ($t.data('pseudo') != 'pseudo') {
						data[i] = $t.data('id');
					}
				});
				$.ajax({
					type: 'POST',
					url: _APP_BASEURL + 'ajax',
					dataType: 'json',
					data: data,
					success : function(results){
						if (results.status == 'ERROR') {
							alert('There was an error saving the order of your pages.');
						}
					}
				});
			}
		});
	});

})(window, jQuery);