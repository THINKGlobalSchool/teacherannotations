<?php
/**
 * Teacher Annotations Annotate Sticky Note Action
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$note_guid = get_input('guid');
$comment = get_input('comment');

if (empty($comment)) {
	register_error(elgg_echo("teacherannotations:error:commentblank"));
	forward(REFERER);
}

// Grab note
$note = get_entity($note_guid);
if (!elgg_instanceof($note, 'object', 'ta_sticky_note')) {
	register_error(elgg_echo("teacherannotations:error:invalidsticky"));
	forward(REFERER);
}

$user = elgg_get_logged_in_user_entity();

$annotation = create_annotation(
	$note->guid,
	'ta_sticky_note_comment',
	$comment,
	"",
	$user->guid,
	$note->access_id
);

// Check annotations
if (!$annotation) {
	register_error(elgg_echo("teacherannotations:error:comment"));
	forward(REFERER);
}


$user = elgg_get_logged_in_user_entity();
$entity = get_entity($note->posted_to_entity_guid);

// If commenting on someone else's note, let the note owner though
if ($note->owner_guid != $user->guid) {
	notify_user($note->owner_guid,
				$user->guid,
				elgg_echo('teacherannotations:notification:stickynotecomment:subject'),
				elgg_echo('teacherannotations:notification:stickynotecommentowner:body', array(
					$user->name,
					$comment,
					$entity->getURL(),
				))
	);
}

if ($entity->getType() != 'user' && $entity->owner_guid != $user->guid) {
	// If we're posting on a regular object, notify the owner that there is a comment on a sticky on their item
	notify_user($entity->owner_guid,
				$user->guid,
				elgg_echo('teacherannotations:notification:stickynotecomment:subject'),
				elgg_echo('teacherannotations:notification:stickynotecommentitem:body', array(
					$user->name,
					$comment,
					$entity->getURL(),
				))
			);
} else if ($entity->getType() == 'user' && $entity->guid != $user->guid) {
	// If we're commenting on a note applied to a user, let the user know there is a comment on a sticky on their profile
	notify_user($entity->guid,
				$user->guid,
				elgg_echo('teacherannotations:notification:stickynotecomment:subject'),
				elgg_echo('teacherannotations:notification:stickynotecommentuser:body', array(
					$user->name,
					$comment,
					$entity->getURL(),
				))
			);
}

echo json_encode(array('annotation_id' => $annotation, 'guid' => $note_guid, 'comment' => $comment));
system_message(elgg_echo('teacherannotations:success:comment'));
forward(REFERER);