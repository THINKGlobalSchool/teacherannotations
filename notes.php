<?php
/* Notes Demo */
elgg_load_css('lightbox');
gatekeeper();

$params = array(
	'filter' => '',
	'title' => 'Teacher Annotations'
);

$form_vars = array(
	'id' => 'ta-add-sticky-note-form',
	'name' => 'ta-add-sticky-note-form'
);

$add_sticky_form = elgg_view_form('teacherannotations/stickynote/save', $form_vars);

$options = array(
	'type' => 'object',
	'subtype' => 'ta_sticky_note',
	'limit' => 0
);

$notes = elgg_get_entities($options);

foreach($notes as $note) {
	$notes_content .= elgg_view('teacherannotations/stickynote', array('entity' => $note));
}

$params['content'] = <<<HTML
	<a id="ta-sticky-note-add" class="elgg-lightbox" href="#ta-add-sticky-note-form">Add</a>
	$notes_content
	<div id="popup-sticky-form">
		$add_sticky_form
	</div>
HTML;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
