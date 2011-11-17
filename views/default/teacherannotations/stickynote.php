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
$date = elgg_view_friendly_time($note->time_updated);

if ($note->canEdit()) {
	$edit_label = elgg_echo('teacherannotations:label:edit');
	$delete_label = elgg_echo('teacherannotations:label:delete');
	$resolve_label = elgg_echo('teacherannotations:label:resolve');
	$edit = <<<HTML
		<a href="{$note->guid}" class="ta-sticky-note-edit">$edit_label</a> |
		<a href="{$note->guid}" class="ta-sticky-note-resolve">$resolve_label</a> |
		<a href="{$note->guid}" class="ta-sticky-note-delete">$delete_label</a>
HTML;
} else {
	$edit = "&nbsp;";
}

$comment_label = elgg_echo('teacherannotations:label:comment');
$comments = elgg_view('teacherannotations/stickynotecomments', array('note_guid' => $note->guid));

if ($note->access_id == ACCESS_LOGGED_IN) {
	$access_display = elgg_echo('teacherannotations:label:accessloggedin');
} else {
	$acl = get_access_collection($note->access_id);
	$access_display = $acl->name;
}

$content = <<<HTML
	<div tabindex="{$note->z}" class="hidden ta-actionable ta-sticky-note ta-draggable {$note->color}" style="left:{$note->x}px;top:{$note->y}px;width:{$note->width}px;z-index:{$note->z};">
		<div class="ta-sticky-note-author">
			$owner_icon
			<div class="ta-sticky-note-author-info">
				<!-- not sure about the owner name here... names *could* be long {$owner->name}<br /> -->
				<span>{$owner_link}</span>
				<span class="elgg-subtext">$date</span>
				<span class="ta-sticky-note-actions ta-sticky-note-edit-container">
					$edit
				</span>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div class="ta-sticky-note-body">$note->description</div>
		<div class="ta-sticky-note-comments-container">
			<div class='ta-sticky-note-comment-list'>$comments</div>
			<a href="{$note->guid}" class="ta-sticky-note-actions ta-sticky-note-comment">$comment_label</a>
		</div>
		<span class="ta-sticky-note-guid">$note->guid</span>
		<span class='ta-sticky-note-access-display'>$access_display</span>
	</div>
HTML;

echo $content;