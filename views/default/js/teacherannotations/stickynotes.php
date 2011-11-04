<?php
/**
 * Teacher Annotations Stickynotes JS Library
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
//<script>
elgg.provide('elgg.teacherannotations.stickynotes');

var zIndex = 0;

// Init function
elgg.teacherannotations.stickynotes.init = function() {
	
	// temp variable for sticky z-indexes
	var tmp;

	$('.ta-sticky-note').each(function(){
		// Finding the biggest z-index value of the notes
		tmp = $(this).css('z-index');
		if (tmp > zIndex) {
			zIndex = tmp;
		} 
	})

	// Make sticky notes draggable
	elgg.teacherannotations.stickynotes.makeDraggable($('.ta-sticky-note.ta-draggable'));

	// Listening for keyup events on fields of the "Add a note" form:
	$('.ta-sticky-note-preview-body').live('keyup',function(e){

		if (!this.preview) {
			this.preview = $('#ta-sticky-note-preview');
		}

		// Setting the text of the preview to the contents of the input field, and stripping all the HTML tags:
		this.preview.find($(this).attr('id').replace('ta-sticky-note-preview-','.ta-sticky-note-'))
					.html($(this)
					.val()
					.replace(/<[^>]+>/ig,''));
	});

	// Changing the color of the preview note:
	$('.ta-sticky-note-color').live('click',function(){
		$('#ta-sticky-note-preview').removeClass('yellow green blue').addClass($(this).attr('class').replace('color',''));
	});
	
	// Submit button
	$("#ta-sticky-submit").live('click', function(event) {
		$(this).replaceWith("<div class='elgg-ajax-loader'></div>");

		var $inputs = $("#ta-add-sticky-form :input");

		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});

		elgg.action('teacherannotations/stickynote/save', {
			data: {
				description: values['description'],
				color: $.trim($('#ta-sticky-note-preview').attr('class').replace('ta-sticky-note','')),
				z: ++zIndex,
				x: 0,
				y: 0,
			},
			success: function(data) {
				if (data.status != -1) {
					var tmp = $("#ta-sticky-note-preview").clone();
					
					tmp.find('span.data').text(data.output).end().css({'z-index':zIndex,top:0,left:0});
					tmp.attr('id', '');
					tmp.appendTo($("#ta-sticky-notes-main"));
					
					elgg.teacherannotations.stickynotes.makeDraggable(tmp);
					
					$.fancybox.close();
				}
			}
		});

		event.preventDefault();
	});
}

// Make draggable helper function
elgg.teacherannotations.stickynotes.makeDraggable = function(elements) {

	// Elements is a jquery object: 
	elements.draggable({
		containment: 'parent',
		start: function(e,ui) { 
			ui.helper.css('z-index',++zIndex);
		},
		stop: function(e,ui) {			
			elgg.action('teacherannotations/stickynote/save', {
				data: {
					z: zIndex,
					x: ui.position.left,
					y: ui.position.top,
					guid: parseInt(ui.helper.find('span.data').html())
				},
				success: function(data) {
					if (data.status != -1) {
						console.log('updated');
					}
				}
			});	
		}
	});
}

elgg.register_hook_handler('init', 'system', elgg.teacherannotations.stickynotes.init);
//</script>