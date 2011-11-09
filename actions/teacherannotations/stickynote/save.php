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
$quiet = get_input('quiet', FALSE);

if (!$guid) {
	$description = get_input('description', NULL);
	$color = get_input('color', TA_COLOR_YELLOW);
	$z = get_input('z', 0);
	$x = get_input('x', 0);
	$y = get_input('y', 0);

	$note = new ElggObject();
	$note->subtype = 'ta_sticky_note';
	$note->access_id = ACCESS_LOGGED_IN; //@TODO	
	$note->color = $color;	
} else {
	$note = get_entity($guid);
	if (!elgg_instanceof($note, 'object', 'ta_sticky_note')) {
		if (!$quiet) {
			register_error(elgg_echo('teacherannotations:error:invalidstickynote'));
		}
		forward(REFERER);
	}

	$description = get_input('description', $note->description);

	if (($description !== "0" && $description !== 0) && empty($description)) {
		$description = NULL;
	}

	$z = get_input('z', $note->z);
	$x = get_input('x', $note->x);
	$y = get_input('y', $note->y);
}

if ($description === NULL) {
	if (!$quiet) {
		register_error(elgg_echo('teacherannotations:error:description'));
	}
	forward(REFERER);
}

$note->description = $description;
$note->z = $z;
$note->x = $x;
$note->y = $y;

if (!$note->save()) {
	if ($quiet) {
		register_error(elgg_echo('teacherannotations:error:savestickynote'));
	}
	forward(REFERER);
}

// @TODO Relationships?
if (!$quiet) {
	system_message(elgg_echo('teacherannotations:success:savestickynote'));
}

$time = $note->time_updated;
if (!$time) {
	$time = time();
}

$friendly_time = elgg_get_friendly_time($time);

echo json_encode(array(
	'guid' =>$note->guid,
	'time' => $time,
	'friendly_time' => $friendly_time
));
forward(REFERER);