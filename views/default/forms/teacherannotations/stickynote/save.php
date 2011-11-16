<?php
/**
 * Teacher Annotations Sticky Note Save Form
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$entity = elgg_extract('entity', $vars, NULL);    // The entity to relate this note to

$entity_input = elgg_view('input/hidden', array(
	'name' => 'entity_guid',
	'id' => 'ta-sticky-note-entity-guid',
	'value' => $entity->guid
));

$description_label = elgg_echo('teacherannotations:label:description');
$description_input = elgg_view('input/plaintext', array(
	'name' => 'description',
	'value' => $description,
	'class' => 'ta-sticky-note-preview-body',
	'id' => 'ta-sticky-note-preview-body'
));

$color_label = elgg_echo('teacherannotations:label:color');

$access_label = elgg_echo('teacherannotations:label:access');
$access_input = elgg_view('input/dropdown', array(
	'class' => 'ta-sticky-note-access-dropdown',
	'name' => 'access_id',
	'options_values' => array(
		ACCESS_LOGGED_IN => elgg_echo('teacherannotations:label:accessloggedin'),
		ACCESS_TA_PRIVATE => elgg_echo('teacherannotations:label:accessprivate')
	)
));

$default_access = elgg_echo('teacherannotations:label:accessloggedin');

$submit_input = elgg_view('input/submit', array(
	'id' => 'ta-sticky-submit',
	'value' => elgg_echo('submit'),
));

$owner = elgg_get_logged_in_user_entity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = "<a href='{$owner->getURL()}'>{$owner->name}</a>";

$content = <<<HTML
	<h3>Add a new note</h3>
	
	<!-- Preview -->
	<div id="ta-sticky-note-preview" class="ta-sticky-note yellow" style="left:0;top:35px;z-index:1">
		<div class="ta-sticky-note-author hidden">
			$owner_icon
			<div class="ta-sticky-note-author-info">
				<!-- not sure about the owner name here... names *could* be long {$owner->name}<br /> -->
				<span>{$owner_link}</span>
				<span class="elgg-subtext"></span>
				<span class="ta-sticky-note-actions ta-sticky-note-edit-container">
				</span>
			</div>
			<div style="clear: both;"></div>
		</div>
		<div class="ta-sticky-note-body"></div>
		<div class="ta-sticky-note-comments-container">
			<div class='ta-sticky-note-comment-list'></div>
		</div>
		<span class="data"></span>
		<span class="ta-sticky-note-access-display">$default_access</span>
	</div>
	
	<!-- Note Form -->
	<div id="ta-sticky-note-add-form">
		<label>$description_label</label>
		$description_input
		<label>$color_label</label>
		<div class="ta-sticky-note-color yellow preview"></div>
		<div class="ta-sticky-note-color blue preview"></div>
		<div class="ta-sticky-note-color green preview"></div>
		<div class="ta-sticky-note-color orange preview"></div>
		<div class="ta-sticky-note-color purple preview"></div>
		<div style="clear:both;"></div><br />
		<div>
			<label>$access_label</label>
			$access_input
		</div>
		<br />$submit_input
		$entity_input
	</div>
HTML;

echo $content;