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

elgg.teacherannotations.stickynotes.zIndex = 0;

// Init function
elgg.teacherannotations.stickynotes.init = function() {

	// temp variable for sticky z-indexes
	var tmp;

	$('.ta-sticky-note').each(function(){
		// Finding the biggest z-index value of the notes
		tmp = $(this).css('z-index');
		if (parseInt(tmp) > parseInt(elgg.teacherannotations.stickynotes.zIndex)) {
			elgg.teacherannotations.stickynotes.zIndex = tmp;
		}
	});

	// Make sticky notes draggable
	elgg.teacherannotations.stickynotes.makeDraggable($('.ta-sticky-note.ta-draggable'));

	$('.ta-sticky-note.ta-actionable').live('mousedown', elgg.teacherannotations.stickynotes.onMousedown);

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
		$('#ta-sticky-note-preview')
			.removeClass('yellow green blue orange purple')
			.addClass($.trim($(this).attr('class').replace('ta-sticky-note-color','')));
	});

	// Submit button
	$("#ta-sticky-submit").live('click', function(event) {
		// Grab a copy of the submit button
		$_submit = $(this);

		// Replace with spinner
		$(this).replaceWith("<div id='ta-sticky-submit' class='elgg-ajax-loader'></div>");

		var $inputs = $("#ta-add-sticky-form :input");

		var values = {};
		$inputs.each(function() {
			values[this.name] = $(this).val();
		});

		elgg.action('teacherannotations/stickynote/save', {
			data: {
				description: values['description'],
				color: $.trim($('#ta-sticky-note-preview').attr('class').replace('ta-sticky-note','')),
				z: ++elgg.teacherannotations.stickynotes.zIndex,
				x: 0,
				y: 0,
			},
			success: function(data) {
				if (data.status != -1) {
					// Clone our preview note to add it to the page
					var tmp = $("#ta-sticky-note-preview").clone();
					var guid = data.output.guid

					// Set guid
					tmp.find('span.data').text(guid).end();

					// Set time
					tmp.find('span.elgg-subtext').text(data.output.friendly_time).end();
					
					// Add buttons
					tmp.find('span.ta-sticky-note-actions').html("<a href='" + guid + "' class='ta-sticky-note-edit' >Edit</a> | <a href='" + guid + "' class='ta-sticky-note-delete'>Delete</a>");

					tmp.find('div.elgg-avatar').removeClass('hidden');

					// Set z-index
					tmp.css({'z-index':elgg.teacherannotations.stickynotes.zIndex,top:"55px",left:"45px"});

					// Clear preview id
					tmp.attr('id', '');

					// Add actionable class
					tmp.addClass('ta-actionable');

					// Append to page
					tmp.appendTo($("#ta-sticky-notes-main"));

					// Make it draggable
					elgg.teacherannotations.stickynotes.makeDraggable(tmp);

					// Close form
					$.fancybox.close();

					// Reset form
					$("#ta-sticky-submit").replaceWith($_submit);

					$inputs.each(function() {
						$(this).val('');
					});

					// Clear preview note
					$("#ta-sticky-note-preview")
						.removeClass('yellow green blue orange purple')
						.addClass('yellow')
						.find(".ta-sticky-note-body").html('');
				}
			}
		});
		event.preventDefault();
	});

	$('.ta-sticky-note-delete').live('click', elgg.teacherannotations.stickynotes.deleteNote);
	//$('.ta-sticky-note-edit').live('click', elgg.teacherannotations.stickynotes.edit);
}

// Make draggable helper function
elgg.teacherannotations.stickynotes.makeDraggable = function(elements) {
	// Elements is a jquery object: 
	elements.appendTo('body').draggable({
		containment: 'window', // was 'parent' by default
		start: function(e,ui) { 
			ui.helper.css('z-index',++elgg.teacherannotations.stickynotes.zIndex);
		},
		stop: function(e,ui) {			
			elgg.action('teacherannotations/stickynote/save', {
				data: {
					quiet: true,
					z: elgg.teacherannotations.stickynotes.zIndex,
					x: ui.position.left,
					y: ui.position.top,
					guid: parseInt(ui.helper.find('span.data').html())
				},
				success: function(data) {
					if (data.status != -1) {
						// ..
					}
				}
			});	
		}
	});
}

// Mousedown helper
elgg.teacherannotations.stickynotes.onMousedown = function(event) {
	$('.ta-sticky-note').each(function(){
		// Make all other notes slightly transparent
		if ($(this).attr('id') == 'ta-sticky-note-preview') {
			return true;
		}
		//$(this).css({ opacity: 0.4 });
	});

	$(this).css({
		opacity: 1,
		'z-index': ++elgg.teacherannotations.stickynotes.zIndex,
	});

	event.preventDefault();
}

// Delete sticky note
elgg.teacherannotations.stickynotes.deleteNote = function(event) {
	var guid = $(this).attr('href');
	var $_this = $(this);

	// Delete action
	elgg.action('teacherannotations/stickynote/delete', {
		data: {
			guid: guid
		},
		success: function(data) {
			if (data.status != -1) {
				$_this.closest(".ta-sticky-note").fadeOut(function() {
					$(this).remove();
				});
			}
		}
	});
	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.teacherannotations.stickynotes.init);
//</script>