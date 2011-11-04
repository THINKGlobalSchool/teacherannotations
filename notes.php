<?php
/* Notes Demo */
elgg_load_css('lightbox');

$params = array(
	'filter' => '',
	'title' => 'Teacher Annotations'
);

$form_vars = array(
	'id' => 'ta-add-sticky-form', 
	'name' => 'ta-add-sticky-form'
);

$add_sticky_form = elgg_view_form('teacherannotations/stickynote/save', $form_vars);

$options = array(
	'type' => 'object',
	'subtype' => 'ta_sticky_note',
	'limit' => 0
);

$notes = elgg_get_entities($options);

foreach($notes as $note) {
	$owner = $note->getOwnerEntity();
	$notes_content .= <<<HTML
		<div class="ta-sticky-note ta-draggable {$note->color}" style="left:{$note->x}px;top:{$note->y}px;z-index:{$note->z};">
			$note->description
			<div class="ta-sticky-note-author">$owner->name</div>
			<span class="data">$note->guid</span>
		</div>
HTML;
}

$params['content'] = <<<HTML
	<div id="ta-sticky-notes-main">
		<a id="ta-sticky-note-add" class="elgg-lightbox" href="#ta-add-sticky-form">Add</a>
		$notes_content
	</div>
	<div id="popup-sticky-form">
		$add_sticky_form
	</div>
HTML;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
