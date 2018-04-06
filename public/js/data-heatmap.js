(function( $ ) {
	'use strict';

    $(function() {

		$('td.data-heatmap-cell').mouseenter(function() {

			var hoverText = $('<div id="data-heatmap-hover">'+$(this).attr('value')+'</div>');

			// hoverText.hide().fadeIn(500);

			$('body').append(hoverText)

		});

		$('.data-heatmap-cell').mousemove(function(e){

			$('#data-heatmap-hover').css({
		        'left': '' + (e.pageX - 50) + 'px',
		        'top': '' +  (e.pageY - 50) + 'px'
    		});

		});

		$('td.data-heatmap-cell').mouseout(function() {

			$('#data-heatmap-hover').remove();

    	});

	});

})( jQuery );
