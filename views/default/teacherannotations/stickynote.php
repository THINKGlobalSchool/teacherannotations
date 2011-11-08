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
$content = <<<HTML
	<div class="ta-sticky-note ta-draggable {$note->color}" style="left:{$note->x}px;top:{$note->y}px;z-index:{$note->z};">
		$note->description
		<div class="ta-sticky-note-author">$owner->name</div>
		<span class="data">$note->guid</span>
	</div>
HTML;

echo $content;