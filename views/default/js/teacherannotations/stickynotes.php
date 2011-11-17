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

// Global z-index variable
elgg.teacherannotations.stickynotes.zIndex = 0;

// Define sticky note colors
elgg.teacherannotations.stickynotes.colors = ['yellow', 'green', 'blue', 'orange', 'purple'];

// Init function
elgg.teacherannotations.stickynotes.init = function() {
	/** GENERAL INIT TASKS **/

	// temp variable for sticky z-indexes
	var tmp;

	// Finding the biggest z-index value of the notes
	$('.ta-sticky-note').each(function(){
		tmp = $(this).css('z-index');
		if (parseInt(tmp) > parseInt(elgg.teacherannotations.stickynotes.zIndex)) {
			elgg.teacherannotations.stickynotes.zIndex = tmp;
		}
	});

	// Create new container box, this will 'contain' the sticky notes
	// It has a width/height of 1px, with overflow: visible
	$('body').append("<div id='ta-sticky-notes-container'></div>");

	// Create new boundary box, this box has a z-index of -1 and acts
	// as the boundary mask for sticky notes
	$('body').append("<div id='ta-sticky-notes-boundary'></div>")

	// Grab some elements to calculate x,y's
	var $inner = $('.elgg-page-body > .elgg-inner');
	var $topbar = $('.elgg-page-topbar');
	var $page = $('.elgg-page');

	// Size the container box
	$("#ta-sticky-notes-container").css({left: $inner.offset().left + 'px',top: $topbar.height() + 'px'});

	// Size the boundary box
	$("#ta-sticky-notes-boundary").css({
		left: $inner.offset().left + 'px',
		top: $topbar.height() + 'px',
		width: $inner.width() + 'px',
		height: ($page.height() - $topbar.height()) + 'px',
	});

	/** EVENT HANDLERS **/

	// Need to update some elements if the window resizes
	$(window).resize(elgg.teacherannotations.stickynotes.onResize);

	// Make sticky notes draggable
	elgg.teacherannotations.stickynotes.initNotes($('.ta-sticky-note.ta-draggable'));

	// Do something on mousedown???
	//$('.ta-sticky-note.ta-actionable').live('mousedown', elgg.teacherannotations.stickynotes.onMousedown);

	// Listening for keyup events on fields of the "Add a note" form:
	$('.ta-sticky-note-preview-body').live('keyup', elgg.teacherannotations.stickynotes.previewKeyup);

	// Changing the color of the preview note:
	$('.ta-sticky-note-color.preview').live('click', elgg.teacherannotations.stickynotes.previewColorClick);

	// Handle change event for the access dropdown
	$('.ta-sticky-note-access-dropdown').live('change', elgg.teacherannotations.stickynotes.accessChange);

	// Submit button
	$("input#ta-sticky-submit").live('click', elgg.teacherannotations.stickynotes.submit);

	// Delete click handler
	$('.ta-sticky-note-delete').live('click', elgg.teacherannotations.stickynotes.deleteClick);

	// Resolve click handler
	$('.ta-sticky-note-resolve').live('click', elgg.teacherannotations.stickynotes.resolveClick);

	// Edit click handler
	$('.ta-sticky-note-edit').live('click', elgg.teacherannotations.stickynotes.editClick);

	// Edit color click handler
	$('.ta-sticky-note-color.edit').live('click', elgg.teacherannotations.stickynotes.editColorClick);

	// Cancel edit click handler
	$('.ta-sticky-note-edit-cancel').live('click', elgg.teacherannotations.stickynotes.cancelEditClick);

	// Edit submit handler
	$('.ta-sticky-notes-edit-submit-button').live('click', elgg.teacherannotations.stickynotes.editSubmit);

	// Comment click handler
	$('.ta-sticky-note-comment').live('click', elgg.teacherannotations.stickynotes.commentClick);

	// Cancel comment click handler
	$('.ta-sticky-note-comment-cancel').live('click', elgg.teacherannotations.stickynotes.cancelCommentClick);

	// Comment submit handler
	$('.ta-sticky-notes-comment-submit-button').live('click', elgg.teacherannotations.stickynotes.commentSubmit);

	// Hide notes click handler
	$('.ta-sticky-notes-hide').live('click', elgg.teacherannotations.stickynotes.hideClick);

	// Show notes click handler
	$('.ta-sticky-notes-show').live('click', elgg.teacherannotations.stickynotes.showClick);
}

// Make draggable helper function
elgg.teacherannotations.stickynotes.initNotes = function($elements) {
	// Grab the boundary box
	var $boundary = $("#ta-sticky-notes-boundary");

	// Calculate boundary values
	var x1 = $boundary.offset().left;
	var y1 = $boundary.offset().top;
	var x2 = $boundary.offset().left + $boundary.width() - 186;
	var y2 = $boundary.offset().top + $boundary.height() - 186;

	// Add notes to the sticky note container
	$elements.appendTo('#ta-sticky-notes-container').fadeIn('slow').removeClass('hidden');

	// Make resizable
	$elements.resizable({
		handles: "e",
		maxWidth: 300,
		minWidth: 165,
		resize: function(e,ui) {
			// Need the height to always be set to auto so that
			// the note adjusts appropriately
			ui.element.css('height', 'auto');
		},
		stop: function(e,ui) {
			elgg.action('teacherannotations/stickynote/save', {
				data: {
					morphing: true,
					quiet: true,
					width: ui.size.width,
					guid: parseInt(ui.helper.find('span.ta-sticky-note-guid').html())
				},
				success: function(data) {
					if (data.status != -1) {
						// ..
					} else {
						console.log(data);
					}
				}
			});
		},
	});

	// Make draggable
	$elements.draggable({
		containment: [x1,y1,x2,y2], // Dynamic coords
		start: function(e,ui) { 
			ui.helper.css('z-index',++elgg.teacherannotations.stickynotes.zIndex);
		},
		stop: function(e,ui) {			
			elgg.action('teacherannotations/stickynote/save', {
				data: {
					morphing: true,
					quiet: true,
					z: elgg.teacherannotations.stickynotes.zIndex,
					x: ui.position.left,
					y: ui.position.top,
					guid: parseInt(ui.helper.find('span.ta-sticky-note-guid').html())
				},
				success: function(data) {
					if (data.status != -1) {
						// ..
					} else {
						console.log(data);
					}
				}
			});	
		}
	});
}

// Submit stickynote form
elgg.teacherannotations.stickynotes.submit = function(event) {
	// Grab a copy of the submit button
	$_submit = $(this);

	// Replace with spinner
	$(this).replaceWith("<div id='ta-sticky-submit' class='elgg-ajax-loader'></div>");

	var $inputs = $("#ta-add-sticky-note-form :input");

	var values = {};
	$inputs.each(function() {
		values[this.name] = $(this).val();
	});

	elgg.action('teacherannotations/stickynote/save', {
		data: {
			description: values['description'],
			entity_guid: values['entity_guid'],
			access_id: values['access_id'],
			color: $.trim($('#ta-sticky-note-preview').attr('class').replace('ta-sticky-note','')),
			z: ++elgg.teacherannotations.stickynotes.zIndex,
			x: 45,
			y: 55,
		},
		success: function(data) {
			if (data.status != -1) {
				// Clone our preview note to add it to the page
				var tmp = $("#ta-sticky-note-preview").clone();
				var guid = data.output.guid

				// Set guid
				tmp.find('span.ta-sticky-note-guid').text(guid).end();

				// Set time
				tmp.find('span.elgg-subtext').text(data.output.friendly_time).end();

				var actions = ['edit', 'resolve', 'delete'];
				var links = [];

				$edit_container = tmp.find('span.ta-sticky-note-edit-container');

				for (x in actions) {
					var $link = $(document.createElement('a'));
					$link.attr('href', guid);
					$link.addClass('ta-sticky-note-' + actions[x]);
					$link.text(elgg.echo('teacherannotations:label:' + actions[x]));
					$edit_container.append($link);

					// Add a little pipe
					if (x < actions.length - 1) {
						$edit_container.append(' | ');
					}
				}

				// Add comment button
				var $comment_link = $(document.createElement('a'));
				$comment_link.attr('href', guid);
				$comment_link.attr('class', 'ta-sticky-note-actions ta-sticky-note-comment');
				$comment_link.text(elgg.echo('teacherannotations:label:comment'));
				tmp.find('div.ta-sticky-note-comments-container').append($comment_link);

				// Show author info
				tmp.find('div.ta-sticky-note-author').removeClass('hidden');

				// Set z-index
				tmp.css({'z-index':elgg.teacherannotations.stickynotes.zIndex,top:"55px",left:"45px"});

				// Clear preview id
				tmp.attr('id', '');

				// Add actionable class
				tmp.addClass('ta-actionable');

				// Append to page
				tmp.addClass('hidden').appendTo($("#ta-sticky-notes-container")).fadeIn();

				// Make it draggable
				elgg.teacherannotations.stickynotes.initNotes(tmp);

				// Close form
				$.fancybox.close();

				// Reset form
				$("#ta-sticky-submit").replaceWith($_submit);

				$inputs.each(function() {
					if (this.name == 'description') {
						$(this).val('');
					}
				});

				// Clear preview note
				$("#ta-sticky-note-preview")
					.removeClass(elgg.teacherannotations.stickynotes.colors.join(' '))
					.addClass('yellow')
					.find(".ta-sticky-note-body").html('');
			} else {
				// Reset input
				$("#ta-sticky-submit").replaceWith($_submit);
			}
		}
	});
	event.preventDefault();
}

// Keyup handler, updates the preview note
elgg.teacherannotations.stickynotes.previewKeyup = function(event){
	if (!this.preview) {
		this.preview = $('#ta-sticky-note-preview');
	}

	// Setting the text of the preview to the contents of the input field, and stripping all the HTML tags:
	this.preview.find($(this).attr('id').replace('ta-sticky-note-preview-','.ta-sticky-note-'))
				.html($(this)
				.val()
				.replace(/<[^>]+>/ig,''));
}

// Click handler for the preview color chooser
elgg.teacherannotations.stickynotes.previewColorClick = function(event){
	$('#ta-sticky-note-preview')
		.removeClass(elgg.teacherannotations.stickynotes.colors.join(' '))
		.addClass($.trim($(this).attr('class').replace('ta-sticky-note-color','')));
}

// Change handler for the access dropdown
elgg.teacherannotations.stickynotes.accessChange = function(event){
	$access_display = $('#ta-sticky-note-preview').find('.ta-sticky-note-access-display');
	if ($(this).val() == 1) {
		$access_display.html(elgg.echo('teacherannotations:label:accessloggedin'));
	} else {
		$access_display.html(elgg.echo('teacherannotations:label:private'));
	}
}

// Mousedown helper (NOT IN USE)
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
		//'z-index': elgg.teacherannotations.stickynotes.zIndex++, don't think I need to do this..
	});
}

// Window resize handler
elgg.teacherannotations.stickynotes.onResize = function(event) {
	// Align the boundary and container with the elgg-inner div
	$("#ta-sticky-notes-boundary").css({left: $('.elgg-page-body > .elgg-inner').offset().left + 'px'});
	$("#ta-sticky-notes-container").css({left: $('.elgg-page-body > .elgg-inner').offset().left + 'px'});

	// Grab the boundary box
	var $boundary = $("#ta-sticky-notes-boundary");

	// Calulate the x,y values for draggable containment
	// Note: This should be smarter.. that 186 is a fixed width..
	var x1 = $boundary.offset().left;
	var y1 = $boundary.offset().top;
	var x2 = $boundary.offset().left + $boundary.width() - 186;
	var y2 = $boundary.offset().top + $boundary.height() - 186;

	// Update draggable containment for notes
	$('.ta-sticky-note.ta-draggable').draggable("option", "containment", [x1, y1, x2, y2])
}

// Delete sticky note
elgg.teacherannotations.stickynotes.deleteClick = function(event) {
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

// Resolve sticky note
elgg.teacherannotations.stickynotes.resolveClick = function(event) {
	var guid = $(this).attr('href');
	var $_this = $(this);

	// Delete action
	elgg.action('teacherannotations/stickynote/resolve', {
		data: {
			guid: guid
		},
		success: function(data) {
			if (data.status != -1) {
				// ..
			}
		}
	});
	event.preventDefault();
}

// Click handler for edit note
elgg.teacherannotations.stickynotes.editClick = function(event) {
	var $_this = $(this);
	var guid = $(this).attr('href');
	var $note = $(this).closest('.ta-sticky-note');
	var $body = $note.find('div.ta-sticky-note-body');
	var value = $body.html();

	// Create cancel link
	var $cancel_link = $(document.createElement('a'));
	$cancel_link.attr('href', guid);
	$cancel_link.addClass('ta-sticky-note-edit-cancel');
	$cancel_link.text(elgg.echo('teacherannotations:label:cancel'));

	// Store the edit link as data within the cancel link
	$cancel_link.data('original', $_this);

	// Replace edit link with cancel
	$(this).replaceWith($cancel_link);

	// Hide the comment area
	$note.find(".ta-sticky-note-comments-container").hide();

	// Hide the access area
	$note.find('.ta-sticky-note-access-display').hide();

	// Create the textarea
	var $textarea = $(document.createElement('textarea'));
	$textarea.addClass('ta-sticky-note-edit-body');
	$textarea.attr('name', 'body');
	$textarea.html(value);

	// Store original value as data within the textarea
	$textarea.data('original', value);

	// Create the access dropdown
	var $select = $(document.createElement('select'));
	$select.attr('class', 'elgg-input-dropdown ta-sticky-note-access-edit-dropdown');

	// Create options
	var $option_logged_in = $(document.createElement('option'));
	$option_logged_in.val('1');
	$option_logged_in.html(elgg.echo('teacherannotations:label:accessloggedin'));

	var $option_private = $(document.createElement('option'));
	$option_private.val('-42');
	$option_private.html(elgg.echo('teacherannotations:label:accessprivate'));

	// Add options to select
	$select.append($option_logged_in);
	$select.append($option_private);

	// Set current access value
	if ($note.find('.ta-sticky-note-access-display').html() == elgg.echo('teacherannotations:label:accessloggedin')) {
		$select.val('1');
	} else {
		$select.val('-42');
	}

	// Create save button
	var $save = $(document.createElement('input'));
	$save.attr({
		class: 'ta-sticky-notes-edit-submit-button elgg-button elgg-button-submit',
		type: 'submit',
		value: elgg.echo('teacherannotations:label:save'),
		name: 'ta-sticky-note-edit-submit'
	});

	// Add new content to the body div
	$body.html($textarea);
	$body.append($select);
	$body.append($save);

	// Create color inputs
	var $colors = $(document.createElement('div'));
	$colors.addClass('ta-sticky-note-edit-color');

	for (x in elgg.teacherannotations.stickynotes.colors) {
		var $color = $(document.createElement('div'));
		$color.attr('class', 'ta-sticky-note-color edit ' + elgg.teacherannotations.stickynotes.colors[x]);
		$colors.append($color);
	}

	$body.append($colors);

	$textarea.focus();

	event.preventDefault();
}

// Click handler for cancel edit
elgg.teacherannotations.stickynotes.cancelEditClick = function(event) {
	var $note = $(this).closest('.ta-sticky-note');
	var $body = $note.find('div.ta-sticky-note-body');
	var $textarea = $note.find('textarea[name="body"]');

	// Replace this cancel link with the origan edit link
	$(this).replaceWith($(this).data('original'));

	// Show the comment area
	$note.find(".ta-sticky-note-comments-container").show();

	// Show the access area
	$note.find(".ta-sticky-note-access-display").show();

	// Replace the textarea with the original value
	$body.html($textarea.data('original'));

	// Replace note class to reset color
	$note.attr('class', $note.data('original_class'));

	event.preventDefault();
}

// Click handler for editing a notes color
elgg.teacherannotations.stickynotes.editColorClick = function(event){
	var $note = $(this).closest('.ta-sticky-note');

	$note.data('original_class', $note.attr('class'));

	var color = $.trim($(this).attr('class').replace('ta-sticky-note-color',''));

	$note.removeClass(elgg.teacherannotations.stickynotes.colors.join(' ')).addClass(color);

	$note.data('new_color', color);
}

// Edit note submit handler
elgg.teacherannotations.stickynotes.editSubmit = function(event) {
	var $note = $(this).closest('.ta-sticky-note');
	var $textarea = $note.find('textarea[name="body"]');
	var $save = $note.find(".ta-sticky-notes-edit-submit-button");
	var $select = $note.find('.ta-sticky-note-access-edit-dropdown');
	var $access_display = $note.find('.ta-sticky-note-access-display');

	var guid = parseInt($note.find('span.ta-sticky-note-guid').html());
	var access_id = $select.val();
	var body = $textarea.val();
	var color = $note.data('new_color');

	$save.attr('disabled', 'disabled');
	$textarea.attr('disabled', 'disabled');
	$select.attr('disabled', 'disabled');

	elgg.action('teacherannotations/stickynote/save', {
		data: {
			access_id: access_id,
			guid: guid,
			description: body,
			color: color,
		},
		success: function(data) {
			if (data.status != -1) {
				// Replace inputs with body value
				$note.find('div.ta-sticky-note-body').html(body);

				// Show the comment area
				$note.find(".ta-sticky-note-comments-container").show();

				// Set access text
				if (access_id == 1) {
					$access_display.html(elgg.echo('teacherannotations:label:accessloggedin'));
				} else {
					$access_display.html(elgg.echo('teacherannotations:label:private'));
				}

				// Show access area
				$access_display.show();

				// Replace cancel link with edit link
				$cancel = $note.find('.ta-sticky-note-edit-cancel');
				$cancel.replaceWith($cancel.data('original'));
			} else {
				$save.removeAttr('disabled');
				$textarea.removeAttr('disabled');
				$select.removeAttr('disabled');
			}
		}
	});

	event.preventDefault();
}

// Comment click handler
elgg.teacherannotations.stickynotes.commentClick = function(event) {
	var $_this = $(this);
	var guid = $(this).attr('href');
	$container = $(this).closest('.ta-sticky-note-comments-container');

	// Create cancel link
	var $cancel_link = $(document.createElement('a'));
	$cancel_link.attr('href', guid);
	$cancel_link.addClass('ta-sticky-note-comment-cancel');
	$cancel_link.text(elgg.echo('teacherannotations:label:cancel'));

	// Store the comment link as data within the cancel link
	$cancel_link.data('original', $_this);

	// Replace edit link with cancel
	$(this).replaceWith($cancel_link);

	// Create the textarea
	var $textarea = $(document.createElement('textarea'));
	$textarea.addClass('ta-sticky-note-edit-body');
	$textarea.attr('name', 'comment');

	// Create save button
	var $save = $(document.createElement('input'));
	$save.attr({
		class: 'ta-sticky-notes-comment-submit-button elgg-button elgg-button-submit',
		type: 'submit',
		value: elgg.echo('teacherannotations:label:save'),
		name: 'ta-sticky-note-comment-submit'
	});

	$container.append($textarea);
	$container.append($save);

	event.preventDefault();
}

// Click handler for cancel comment
elgg.teacherannotations.stickynotes.cancelCommentClick = function(event) {
	var $note = $(this).closest('.ta-sticky-note');
	var $textarea = $note.find('textarea[name="comment"]');
	var $save = $note.find(".ta-sticky-notes-comment-submit-button");

	// Replace this cancel link with the origanal comment link
	$(this).replaceWith($(this).data('original'));

	// Remove textarea and save button
	$textarea.remove();
	$save.remove();

	event.preventDefault();
}

// Comment submit handler
elgg.teacherannotations.stickynotes.commentSubmit = function(event) {
	var $note = $(this).closest('.ta-sticky-note');
	var $textarea = $note.find('textarea[name="comment"]');
	var $save = $note.find(".ta-sticky-notes-comment-submit-button");

	var guid = parseInt($note.find('span.ta-sticky-note-guid').html());
	var comment = $note.find('textarea[name="comment"]').val();

	$save.attr('disabled', 'disabled');
	$textarea.attr('disabled', 'disabled');

	elgg.action('teacherannotations/stickynote/annotate', {
		data: {
			guid: guid,
			comment: comment,
		},
		success: function(data) {
			if (data.status != -1) {
				// Replace cancel link with edit link
				var $cancel = $note.find('.ta-sticky-note-comment-cancel');

				$cancel.replaceWith($cancel.data('original'));
				$textarea.remove();
				$save.remove();

				$comment_list = $note.find('.ta-sticky-note-comment-list');

				var comments_url = elgg.get_site_url() + 'ajax/view/teacherannotations/stickynotecomments?note_guid=' + guid;

				$comment_list.load(comments_url, function() {
					$annotation = $comment_list.find('#item-annotation-' + data.output.annotation_id);
					$annotation.hide(0, function() {
						$(this).fadeIn();
					});
				});
			} else {
				$save.removeAttr('disabled');
				$textarea.removeAttr('disabled');
			}
		}
	});
	event.preventDefault();
}

// Click handler for hide notes link
elgg.teacherannotations.stickynotes.hideClick = function(event) {
	$_this = $(this);

	var left = $(this).offset().left;
	var top = $(this).offset().top;

	// Create show link
	var $show_link = $(document.createElement('a'));
	$show_link.attr('href', '#');
	$show_link.addClass('ta-sticky-notes-show');
	$show_link.text(elgg.echo('teacherannotations:label:show'));

	// Store the hide link as data within the show link
	$show_link.data('original', $_this);

	// Replace hide link
	$(this).replaceWith($show_link);

	$('.ta-sticky-note').each(function(){
		//$(this).data('original_top', $(this).css('top'));
		//$(this).data('original_left', $(this).css('left'));
		$(this).animate({
			//top: top + 'px',
			//left: left + 'px',
		}).fadeOut();
	});

	event.preventDefault();
}

// Click handler for show notes link
elgg.teacherannotations.stickynotes.showClick = function(event) {
	$(this).replaceWith($(this).data('original'));

	$('.ta-sticky-note').each(function(){
		$(this).fadeIn().animate({
			//top: $(this).data('original_top'),
			//left: $(this).data('original_left'),
		});
	});

	event.preventDefault();
}

elgg.register_hook_handler('init', 'system', elgg.teacherannotations.stickynotes.init);
//</script>