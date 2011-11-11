<?php
/**
 * Teacher Annotations Resolve Sticky Note Action
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$note_guid = get_input('guid');

// Grab note
$note = get_entity($note_guid);
if (!elgg_instanceof($note, 'object', 'ta_sticky_note')) {
	register_error(elgg_echo("teacherannotations:error:invalidsticky"));
	forward(REFERER);
}

// Set resolved
$note->resolved = TRUE;

// Try saving
if (!$note->save()) {
	if (!$quiet) {
		register_error(elgg_echo('teacherannotations:error:resolvesticky'));
	}
	forward(REFERER);
}

echo $note->resolved;
system_message(elgg_echo('teacherannotations:success:resolvesticky'));
forward(REFERER);