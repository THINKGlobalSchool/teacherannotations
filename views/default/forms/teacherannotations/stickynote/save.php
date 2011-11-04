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

$description_label = elgg_echo('teacherannotations:label:description');
$description_input = elgg_view('input/plaintext', array(
	'name' => 'description',
	'value' => $description,
	'class' => 'ta-sticky-note-preview-body',
	'id' => 'ta-sticky-note-preview-body'
));

$color_label = elgg_echo('teacherannotations:label:color');

$submit_input = elgg_view('input/submit', array(
	'id' => 'ta-sticky-submit',
	'value' => elgg_echo('submit'),
));

$owner = elgg_get_logged_in_user_entity();

$content = <<<HTML
	<h3>Add a new note</h3>
	
	<!-- Preview -->
	<div id="ta-sticky-note-preview" class="ta-sticky-note yellow" style="left:0;top:35px;z-index:1">
		<div class="ta-sticky-note-body"></div>
		<div class="ta-sticky-note-author">{$owner->name}</div>
		<span class="data"></span>
	</div>
	
	<!-- Note Form -->
	<div id="ta-sticky-note-data">
		<label>$description_label</label>
		$description_input
		<label>$color_label</label>
		<div class="ta-sticky-note-color yellow"></div>
		<div class="ta-sticky-note-color blue"></div>
		<div class="ta-sticky-note-color green"></div>
		<!-- Submit -->
		<div style="clear:both;"></div>
		<br />$submit_input
	
	</div>
HTML;

echo $content;