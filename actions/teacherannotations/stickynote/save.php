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
$entity_guid = get_input('entity_guid', NULL); // The entity to relate this note to
$quiet = get_input('quiet', FALSE);
$morphing = get_input('morphing', FALSE); // Means we're JUST moving/resizing the note
$access_id = get_input('access_id', ACCESS_LOGGED_IN);

if (!$guid) {
	// Require the entity!
	$entity = get_entity($entity_guid);
	if (!$entity_guid) {
		if (!$quiet) {
			register_error(elgg_echo('teacherannotations:error:entity'));
		}
		forward(REFERER);
	}

	$color = get_input('color', TA_COLOR_YELLOW);
	$description = get_input('description', NULL);

	$z = get_input('z', 0);
	$x = get_input('x', 0);
	$y = get_input('y', 0);
	$width = get_input('width', 165);

	$note = new ElggObject();
	$note->subtype = 'ta_sticky_note';
	$note->posted_to_entity_guid = $entity->guid; // Store the entity guid as metadata as well
	$note->posted_to_owner_guid = $entity->owner_guid; // Store the owner guid of the entity as well (future use)
	$note->access_id = $access_id;
} else {
	$note = get_entity($guid);
	if (!elgg_instanceof($note, 'object', 'ta_sticky_note')) {
		if (!$quiet) {
			register_error(elgg_echo('teacherannotations:error:invalidstickynote'));
		}
		forward(REFERER);
	}

	$description = get_input('description', $note->description);

	$color = get_input('color', $note->color);
	$z = get_input('z', $note->z);
	$x = get_input('x', $note->x);
	$y = get_input('y', $note->y);
	$width = get_input('width', $note->width);

	// If not morphing
	if (!$morphing) {
		// If access id is private, set it to the private acl
		if ($access_id == ACCESS_TA_PRIVATE) {
			$note->access_id = $note->ta_acl;
		} else {
			// Logged in users here, go ahead and set that
			$note->access_id = $access_id;
		}
	}
}

// Make sure there is some content in description
if (($description !== "0" && $description !== 0) && empty($description)) {
	if (!$quiet) {
		register_error(elgg_echo('teacherannotations:error:description'));
	}
	forward(REFERER);
}

$note->description = $description;
$note->color = $color;

// We'll ignore access here (for now) to allow others to move notes
$ia = elgg_get_ignore_access();

if ($morphing) {
	elgg_set_ignore_access(TRUE);
} else {
	// ..
}

$note->z = $z;
$note->x = $x;
$note->y = $y;
$note->width = $width;

if (!$note->save()) {
	if (!$quiet) {
		register_error(elgg_echo('teacherannotations:error:savestickynote'));
	}
	forward(REFERER);
}

elgg_set_ignore_access($ia);

// Do stuff if we're creating a new entity, after it is saved
if (!$guid) {
	// Add entity relationship
	add_entity_relationship($note->guid, TA_STICKY_NOTE_RELATIONSHIP, $entity_guid);

	$user = elgg_get_logged_in_user_entity();
	if ($entity->getType() != 'user' && $entity->owner_guid != $user->guid) {
		// If we're posting on a regular object, notify if poster wasn't owner
		notify_user($entity->owner_guid,
					$user->guid,
					elgg_echo('teacherannotations:notification:stickynotecreate:subject'),
					elgg_echo('teacherannotations:notification:stickynotecreate:body', array(
						$user->name,
						$entity->title ? $entity->title : $entity->name,
						$description,
						$entity->getURL(),
					))
				);
	} else if ($entity->getType() == 'user' && $entity->guid != $user->guid) {
		system_message('wtf');
		// If we're posting to a user, notify if posting to another user only
		notify_user($entity->guid,
					$user->guid,
					elgg_echo('teacherannotations:notification:stickynotecreate:subject'),
					elgg_echo('teacherannotations:notification:stickynotecreateuser:body', array(
						$user->name,
						$description,
						$entity->getURL(),
					))
				);
	}
}

if (!$quiet) {
	system_message(elgg_echo('teacherannotations:success:savestickynote'));
}

$time = $note->time_created;
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