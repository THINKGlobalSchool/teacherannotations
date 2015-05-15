<?php
/**
 * Teacher Annotations Sticky Note Comments
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$options = array(
	'guid' => $vars['note_guid'],
	'annotation_name' => 'ta_sticky_note_comment',
	'full_view' => true
);

$html = elgg_list_annotations($options);
if ($html) {
	echo '<h3>' . elgg_echo('comments') . '</h3>';
	echo $html;
}