<?php
/**
 * Teacher Annotations Delete Sticky Note Action
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
$note_guid = get_input('guid');
$note = get_entity($note_guid);

if (elgg_instanceof($note, 'object', 'ta_sticky_note') && $note->canEdit()) {
	if ($note->delete()) {
		system_message(elgg_echo('teacherannotations:success:deletesticky'));
		forward(REFERER);
	} else {
		register_error(elgg_echo('teacherannotations:error:deletesticky'));
	}
} else {
	register_error(elgg_echo('teacherannotations:error:invalidsticky'));
}

forward(REFERER);