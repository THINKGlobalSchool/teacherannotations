<?php
/**
 * Teacher Annotations Add Sticky Note Action
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('guid', NULL);

if (!$guid) {
	$description = get_input('description');
	$color = get_input('color', TA_COLOR_YELLOW);
	$z = get_input('z', 0);
	$x = get_input('x', 0);
	$y = get_input('y', 0);

	if (!$description) {
		register_error(elgg_echo('teacherannotations:error:description'));
	}

	$note = new ElggObject();
	$note->subtype = 'ta_sticky_note';
	$note->access_id = ACCESS_LOGGED_IN; //@TODO	
	$note->description = $description;
	$note->color = $color;	
} else {
	$note = get_entity($guid);
	if (!elgg_instanceof($note, 'object', 'ta_sticky_note')) {
		register_error(elgg_echo('teacherannotations:error:invalidstickynote'));
		forward(REFERER);
	}
	
	$z = get_input('z', $note->z);
	$x = get_input('x', $note->x);
	$y = get_input('y', $note->y);
}

$note->z = $z;
$note->x = $x;
$note->y = $y;

if (!$note->save()) {
	register_error(elgg_echo('teacherannotations:error:savestickynote'));
	forward(REFERER);
}

// @TODO Relationships?
system_message(elgg_echo('teacherannotations:success:savestickynote'));
echo $note->guid;
forward(REFERER);