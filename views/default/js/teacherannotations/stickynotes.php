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

	// Need to update some elements if the window resizes
	$(window).resize(elgg.teacherannotations.stickynotes.onResize);

	// Make sticky notes draggable
	elgg.teacherannotations.stickynotes.makeDraggable($('.ta-sticky-note.ta-draggable'));

	$('.ta-sticky-note.ta-actionable').live('mousedown', elgg.teacherannotations.stickynotes.onMousedown);

	// Listening for keyup events on fields of the "Add a note" form:
	$('.ta-sticky-note-preview-body').live('keyup', elgg.teacherannotations.stickynotes.previewKeyup);

	// Changing the color of the preview note:
	$('.ta-sticky-note-color.preview').live('click', elgg.teacherannotations.stickynotes.previewColorClick);

	// Submit button
	$("#ta-sticky-submit").live('click', elgg.teacherannotations.stickynotes.submit);

	// Delete click handler
	$('.ta-sticky-note-delete').live('click', elgg.teacherannotations.stickynotes.deleteNote);

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
}

// Make draggable helper function
elgg.teacherannotations.stickynotes.makeDraggable = function(elements) {
	// Grab the boundary box
	var $boundary = $("#ta-sticky-notes-boundary");

	// Calculate boundary values
	var x1 = $boundary.offset().left;
	var y1 = $boundary.offset().top;
	var x2 = $boundary.offset().left + $boundary.width() - 186;
	var y2 = $boundary.offset().top + $boundary.height() - 186;

	// Elements is a jquery object: 
	elements.appendTo('#ta-sticky-notes-container').fadeIn('slow').draggable({
		//containment: 'window', // was 'parent' by default
		//containment: '#ta-sticky-notes-boundary',
		containment: [x1,y1,x2,y2], // Dynamic coords
		start: function(e,ui) { 
			ui.helper.css('z-index',++elgg.teacherannotations.stickynotes.zIndex);
		},
		stop: function(e,ui) {			
			elgg.action('teacherannotations/stickynote/save', {
				data: {
					dragging: true,
					quiet: true,
					z: elgg.teacherannotations.stickynotes.zIndex,
					x: ui.position.left,
					y: ui.position.top,
					guid: parseInt(ui.helper.find('span.data').html())
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
				tmp.find('span.data').text(guid).end();

				// Set time
				tmp.find('span.elgg-subtext').text(data.output.friendly_time).end();

				// Add buttons
				var edit_label = elgg.echo('teacherannotations:label:edit');
				var delete_label = elgg.echo('teacherannotations:label:delete');
				tmp.find('span.ta-sticky-note-edit-container').html("<a href='" + guid + "' class='ta-sticky-note-edit' >" + edit_label + "</a> | <a href='" + guid + "' class='ta-sticky-note-delete'>" + delete_label + "</a>");

				// Add comment buttons
				var comment_label = elgg.echo('teacherannotations:label:comment');
				tmp.find('div.ta-sticky-note-comments-container').append("<a href='" + guid + "' class='ta-sticky-note-actions  ta-sticky-note-comment'>" + comment_label + "</a>");

				tmp.find('div.elgg-avatar').removeClass('hidden');

				// Set z-index
				tmp.css({'z-index':elgg.teacherannotations.stickynotes.zIndex,top:"55px",left:"45px"});

				// Clear preview id
				tmp.attr('id', '');

				// Add actionable class
				tmp.addClass('ta-actionable');

				// Append to page
				tmp.addClass('hidden').appendTo($("#ta-sticky-notes-container")).fadeIn();

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
		.removeClass('yellow green blue orange purple')
		.addClass($.trim($(this).attr('class').replace('ta-sticky-note-color','')));
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

// Click handler for edit note
elgg.teacherannotations.stickynotes.editClick = function(event) {
	var $_this = $(this);
	var guid = $(this).attr('href');
	var $note = $(this).closest('.ta-sticky-note');
	var $body = $note.find('div.ta-sticky-note-body');
	var value = $body.html();

	// Create cancel link
	var cancel_label = elgg.echo('teacherannotations:label:cancel');
	$cancel = $("<a href='" + guid + "' class='ta-sticky-note-edit-cancel' >" + cancel_label + "</a>");

	// Replace edit link with cancel
	$(this).replaceWith($cancel);

	// Store the edit link as data within the cancel link
	$cancel.data('original', $_this);

	// Hide the comment area
	$note.find(".ta-sticky-note-comments-container").hide();

	// Create the textarea
	$textarea = $("<textarea class='ta-sticky-note-edit-body' name='body'>" +  value + "</textarea>");

	// Store original value as data within the textarea
	$textarea.data('original', value);

	// Create save button
	$save = $("<input class='ta-sticky-notes-edit-submit-button elgg-button elgg-button-submit' type='submit' value='Save' name='ta-sticky-note-edit-submit' />");

	// Create color inputs
	$colors = $("<div class='ta-sticky-note-edit-color'><div class='ta-sticky-note-color edit yellow'></div><div class='ta-sticky-note-color edit blue'></div><div class='ta-sticky-note-color edit green'></div><div class='ta-sticky-note-color edit orange'></div><div class='ta-sticky-note-color edit purple'></div></div>");

	// Add new content to the body div
	$body.html($textarea);
	$body.append($save);
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

	$note.removeClass('yellow green blue orange purple').addClass(color);

	$note.data('new_color', color);
}

// Edit note submit handler
elgg.teacherannotations.stickynotes.editSubmit = function(event) {
	var $note = $(this).closest('.ta-sticky-note');
	var $textarea = $note.find('textarea[name="body"]');
	var $save = $note.find(".ta-sticky-notes-edit-submit-button");

	var guid = parseInt($note.find('span.data').html());
	var body = $textarea.val();
	var color = $note.data('new_color');

	$save.attr('disabled', 'disabled');
	$textarea.attr('disabled', 'disabled');

	elgg.action('teacherannotations/stickynote/save', {
		data: {
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

				// Replace cancel link with edit link
				$cancel = $note.find('.ta-sticky-note-edit-cancel');
				$cancel.replaceWith($cancel.data('original'));
			} else {
				$save.removeAttr('disabled');
				$textarea.removeAttr('disabled');
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
	var cancel_label = elgg.echo('teacherannotations:label:cancel');
	$cancel = $("<a href='" + guid + "' class='ta-sticky-note-comment-cancel' >" + cancel_label + "</a>");

	// Replace edit link with cancel
	$(this).replaceWith($cancel);

	// Store the comment link as data within the cancel link
	$cancel.data('original', $_this);

	// Create textarea
	$textarea = $("<textarea class='ta-sticky-note-edit-body' name='comment'></textarea>");

	// Create save button
	$save = $("<input class='ta-sticky-notes-comment-submit-button elgg-button elgg-button-submit' type='submit' value='Save' name='ta-sticky-note-comment-submit' />");

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

	var guid = parseInt($note.find('span.data').html());
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

elgg.register_hook_handler('init', 'system', elgg.teacherannotations.stickynotes.init);
//</script>