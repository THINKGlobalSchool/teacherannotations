<?php
/**
 * Teacher Annotations Sticky Note View
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$note = elgg_extract('entity', $vars);

$owner = $note->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = "<a href='{$owner->getURL()}'>{$owner->name}</a>";
$date = elgg_view_friendly_time($note->time_created);

// Set class if note is resolved
if ($note->resolved) {
	$resolved_class = "ta-sticky-note-resolved";
}

// Add edit actions
if ($note->canEdit()) {
	$edit_label = elgg_echo('teacherannotations:label:edit');
	$delete_label = elgg_echo('teacherannotations:label:delete');
	$edit = <<<HTML
		<a href="{$note->guid}" class="ta-sticky-note-edit">$edit_label</a> |
		<a href="{$note->guid}" class="ta-sticky-note-delete">$delete_label</a> |
HTML;

}

$members = get_members_of_access_collection($note->ta_acl, TRUE);

if ($note->canEdit() || in_array(elgg_get_logged_in_user_guid(), $members)) {
	// Add resolve/unresolve link
	if ($note->resolved) {
		$unresolve_label = elgg_echo('teacherannotations:label:unresolve');
		$edit .= " <a href='{$note->guid}' class='ta-sticky-note-unresolve'>$unresolve_label</a>";
	} else {
		$resolve_label = elgg_echo('teacherannotations:label:resolve');
		$edit .= " <a href='{$note->guid}' class='ta-sticky-note-resolve'>$resolve_label</a>";
	}
}


$comment_label = elgg_echo('teacherannotations:label:comment');
$comments = elgg_view('teacherannotations/stickynotecomments', array('note_guid' => $note->guid));

if ($note->access_id == ACCESS_LOGGED_IN) {
	$access_display = elgg_echo('teacherannotations:label:accessloggedin');
} else {
	$acl = get_access_collection($note->access_id);
	$access_display = $acl->name;
}

$description = elgg_view("output/longtext", array("value" => $note->description));

$content = <<<HTML
	<div tabindex="{$note->z}" class="ta-actionable ta-sticky-note ta-draggable {$note->color} {$resolved_class}" style="display: none;left:{$note->x}px;top:{$note->y}px;width:{$note->width}px;z-index:{$note->z};">
		<span class="ta-sticky-note-actions ta-sticky-note-edit-container">
			$edit
		</span>
		<div class='clearfix'></div>
		<div class="ta-sticky-note-author">
			$owner_icon
			<div class="ta-sticky-note-author-info">$blah
				<span>{$owner_link}</span>
				<span class="elgg-subtext">$date</span>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div class="ta-sticky-note-body">$description</div>
		<div class="ta-sticky-note-comments-container">
			<div class='ta-sticky-note-comment-list'>$comments</div>
			<a href="{$note->guid}" class="ta-sticky-note-comment">$comment_label</a>
		</div>
		<span class="ta-sticky-note-guid">$note->guid</span>
		<span class='ta-sticky-note-access-display'>$access_display</span>
	</div>
HTML;

echo $content;