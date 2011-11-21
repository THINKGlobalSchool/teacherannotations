<?php
/**
 * Teacher Annotations Sticky Note Info View
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$notes = elgg_extract('notes', $vars);
$entity = elgg_extract('entity', $vars);


$content = "<div class='ta-sticky-note-info' id='ta-sticky-note-info-{$entity->guid}'>"; 


foreach($notes as $note) {
	$owner = $note->getOwnerEntity();

	$owner_link = "<a href='{$owner->getURL()}'>{$owner->name}</a>";

	$time_created = "<span class='elgg-subtext'>" . elgg_get_friendly_time($note->time_created) . "</span>";

	$added = elgg_echo('teacherannotations:label:noteaddedby', array($owner_link, $time_created));

	$content .= "<span class='ta-note-info'>{$added}</span>";
}

$content .= "</div>";

echo $content;