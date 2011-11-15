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

// notify if poster wasn't owner
$user = elgg_get_logged_in_user_entity();
$entity = get_entity($note->posted_to_entity_guid);

if (elgg_instanceof($entity, 'object') && $note->owner_guid != $user->guid) {
	notify_user($note->owner_guid,
				$user->guid,
				elgg_echo('teacherannotations:notification:stickynotecomment:subject'),
				elgg_echo('teacherannotations:notification:stickynotecomment:body', array(
					$user->name,
					$comment,
					$entity->getURL(),
				))
	);
}

echo json_encode(array('annotation_id' => $annotation, 'guid' => $note_guid, 'comment' => $comment));
system_message(elgg_echo('teacherannotations:success:comment'));
forward(REFERER);