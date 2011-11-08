<?php
/**
 * Teacher Annotations JS Library
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
$date = elgg_view_friendly_time($note->time_updated);

if ($note->canEdit()) {
	$edit = <<<HTML
	<span class="ta-sticky-note-actions">
		<a href="{$note->guid}" class="ta-sticky-note-edit">Edit</a> |
		<a href="{$note->guid}" class="ta-sticky-note-delete">Delete</a>
	</span>
HTML;
}

$content = <<<HTML
	<div tabindex="{$note->z}" class="ta-actionable ta-sticky-note ta-draggable {$note->color}" style="left:{$note->x}px;top:{$note->y}px;z-index:{$note->z};">
		<div class="ta-sticky-note-author">
			$owner_icon
			<div class="ta-sticky-note-author-info">
				<!-- not sure about the owner name here... names *could* be long {$owner->name}<br /> -->
				<span class="elgg-subtext">$date</span>
				$edit
			</div>
			<div style="clear: both;"></div>
		</div>
		<div class="ta-sticky-note-body">$note->description</div>
		<span class="data">$note->guid</span>
	</div>
HTML;

echo $content;