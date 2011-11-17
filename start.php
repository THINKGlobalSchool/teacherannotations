<?php
/**
 * Teacher Annotations Start.php
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * Utilizes code from the following tutorial:
 * http://tutorialzine.com/2010/01/sticky-notes-ajax-php-jquery/
 * 
 * TODO:
 * - Resolve note should hide the note, add menu to show all, hide all and show unresolved
 * - Add some view to advertise that the object is annotated, regardless of permissions
 * - Improve bottom bar
 * - Improve edit/resolve/delete menu layout
 */
elgg_register_event_handler('init', 'system', 'teacher_annotations_init');

function teacher_annotations_init() {

	// Define color constants
	define("TA_COLOR_YELLOW", 'yellow');
	define("TA_COLOR_BLUE", 'blue');
	define("TA_COLOR_GREEN", 'green');
	define("TA_COLOR_ORANGE", 'orange');
	define("TA_COLOR_PURPLE", 'purple');

	// Define relationships
	define('TA_STICKY_NOTE_RELATIONSHIP', 'ta_sticky_note_added_to'); // Relationship for stickies

	// Owner and Object owner access constant
	define('ACCESS_TA_PRIVATE', -42); // Note to self, access_id needs to be an integer...

	// Register and load library
	elgg_register_library('elgg:teacherannotations', elgg_get_plugins_path() . 'teacherannotations/lib/teacherannotations.php');
	elgg_load_library('elgg:teacherannotations');

	// Register CSS
	$t_css = elgg_get_simplecache_url('css', 'teacherannotations/css');
	elgg_register_simplecache_view('css/teacherannotations/css');
	elgg_register_css('elgg.teacherannotations', $t_css);
	elgg_load_css('elgg.teacherannotations');
	
	$ts_css = elgg_get_simplecache_url('css', 'teacherannotations/stickynotes');
	elgg_register_simplecache_view('css/teacherannotations/stickynotes');
	elgg_register_css('elgg.teacherannotations.stickynotes', $ts_css);
	elgg_load_css('elgg.teacherannotations.stickynotes');

	// Register JS libraries
	$t_js = elgg_get_simplecache_url('js', 'teacherannotations/teacherannotations');
	elgg_register_simplecache_view('js/teacherannotations/teacherannotations');
	elgg_register_js('elgg.teacherannotations', $t_js);

	$ts_js = elgg_get_simplecache_url('js', 'teacherannotations/stickynotes');
	elgg_register_simplecache_view('js/teacherannotations/stickynotes');
	elgg_register_js('elgg.teacherannotations.stickynotes', $ts_js);

	// Load JS only if logged in
	if (elgg_is_logged_in()) {
		elgg_load_js('elgg.teacherannotations');
		elgg_load_js('elgg.teacherannotations.stickynotes');
	}
	
	// Register page handler
	elgg_register_page_handler('teacherannotations','teacherannotations_page_handler');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'teacherannotations/actions/teacherannotations';
	elgg_register_action('teacherannotations/stickynote/save', "$action_base/stickynote/save.php");
	elgg_register_action('teacherannotations/stickynote/delete', "$action_base/stickynote/delete.php");
	elgg_register_action('teacherannotations/stickynote/annotate', "$action_base/stickynote/annotate.php");
	elgg_register_action('teacherannotations/stickynote/resolve', "$action_base/stickynote/resolve.php");

	// Register hook handler to add a full_view kind of context to all views
	elgg_register_plugin_hook_handler('view', 'all', 'teacherannotations_entity_full_view_handler');

	// Register general entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'teacherannotations_entity_menu_setup', 9999);

	// Hook into the annotations menu to add sticky notes
	elgg_register_plugin_hook_handler('register', 'menu:teacherannotations', 'teacherannotations_sticky_notes_menu_setup');

	// Register a handler for creating teacher annotations
	elgg_register_event_handler('create', 'object', 'teacherannotation_create_event_listener');

	// Register a handler for deleting teacher annotations
	elgg_register_event_handler('delete', 'object', 'teacherannotation_delete_event_listener');

	return TRUE;
}

/**
 * Teacher annotations page handler
 */
function teacherannotations_page_handler($page) {
	// TESTING CODE!!
	if ($page[0] == 'debug') {
		
		$delete = get_input('delete', FALSE);
		
		echo "<pre>DEBUG MODE!<br /><br />";
		
		$options = array(
			'type' => 'object',
			'subtype' => 'ta_sticky_note',
			'limit' => 0,
			'count' => TRUE
		);
		
		$notes_count = elgg_get_entities($options);
		
		$notes_count = $notes_count ? $notes_count : 0;
		
		unset($options['count']);
		
		echo "Found $notes_count note(s)<br /><br />";
		
		$notes = elgg_get_entities($options);
		
		foreach($notes as $note) {
			$options = array(
				'type' => 'object',
				'limit' => 1,
				'relationship' => TA_STICKY_NOTE_RELATIONSHIP,
				'relationship_guid' => $note->guid,
				'inverse_relationship' => FALSE,
			);

			$obj = elgg_get_entities_from_relationship($options);

			if (!empty($obj)) {
				$related = $obj[0]->guid;
			} else {
				$related = 'NONE!';
			}

			echo "GUID: $note->guid Related: $related Access: $note->access_id<br />";
		}
		
		$url = elgg_get_site_url() . 'teacherannotations/debug';
		
		if (!$delete) {
			echo "<form action='$url' method='GET'>";
			echo "<br /><input type='submit' name='delete' value='Delete Notes' />";
			echo "</form>";
		} else if ($delete) {
			echo "<br />";
			foreach($notes as $note) {
				echo "DELETED: $note->guid<br />";
				$note->delete();
			}	
		}
		echo "</pre>";
	} else {
		include elgg_get_plugins_path() . 'teacherannotations/notes.php';
	}
}



/**
 * Post process object views and add the sticky note extension if we're in
 * a full view.. This isn't the greatest.. but it works.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $return
 * @param unknown_type $params
 * @return unknown
 */
function teacherannotations_entity_full_view_handler($hook, $type, $return, $params) {
	if (!elgg_is_logged_in() || elgg_get_viewtype() != "default") {
		return;
	}

	// Only dealing with straight up object views here
	if (strpos($params['view'], 'object/') === 0              // Check that view is an object view
		&& isset($params['vars']['entity'])                   // Make sure we have an entity
		&& strpos($params['view'], 'object/elements') !== 0   // Ignore object/elements views
		&& $params['vars']['full_view']) {                    // Check for full view

		// Double check entity
		if (!elgg_instanceof($params['vars']['entity'], 'object')) {
			return $return;
		}

		// We might not want to attach sticky notes to certain entities..
		$exceptions = array(
			'feedback',
			'forum',
			'forum_topic',
			'forum_reply',
			'poll',
			'messages',
		);

		if (in_array($params['vars']['entity']->getSubtype(), $exceptions)) {
			return $return;
		}

		// Get annotations menu
		$content = elgg_view_menu('teacherannotations', array(
			'entity' => $params['vars']['entity'],
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz'
		));

		$return .= "<div id='ta-bottom-bar'>$content</div>";
	}
	return $return;
}

/**
 * Register sticky notes menu items
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $return
 * @param unknown_type $params
 * @return unknown
 */
function teacherannotations_sticky_notes_menu_setup($hook, $type, $return, $params) {
 	$options = array(
		'name' => 'ta-sticky-notes',
		'text' => elgg_echo('teacherannotations:label:stickynotes') . ':&nbsp;',
		'href' => FALSE,
		'priority' => 1,
	);

	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'ta-sticky-note-add',
		'text' => elgg_echo('teacherannotations:label:add') . $add_sticky_form,
		'href' => '#ta-add-sticky-note-form',
		'link_class' => 'elgg-lightbox',
		'priority' => 2,
	);

	$return[] = ElggMenuItem::factory($options);

	$options = array(
		'name' => 'ta-sticky-notes-hide',
		'text' =>  elgg_echo('teacherannotations:label:hide') . $add_sticky_form,
		'href' => '#',
		'link_class' => 'ta-sticky-notes-hide',
		'priority' => 4,
	);

	$return[] = ElggMenuItem::factory($options);

	$form_vars = array(
		'id' => 'ta-add-sticky-note-form',
		'name' => 'ta-add-sticky-note-form'
	);

	$add_sticky_form = '<div id="popup-sticky-form">';
	$add_sticky_form .= elgg_view_form('teacherannotations/stickynote/save', $form_vars, array('entity' => $params['entity']));
	$add_sticky_form .= '</div>';

	$options = array(
		'type' => 'object',
		'subtype' => 'ta_sticky_note',
		'limit' => 0,
		'relationship' => TA_STICKY_NOTE_RELATIONSHIP,
		'relationship_guid' => $params['entity']->guid,
		'inverse_relationship' => TRUE,
	);

	$notes = elgg_get_entities_from_relationship($options);

	foreach($notes as $note) {
		$notes_content .= elgg_view('teacherannotations/stickynote', array('entity' => $note));
	}

	$options = array(
		'name' => 'ta-sticky-notes-content',
		'text' => $add_sticky_form . $notes_content,
		'href' => FALSE,
		'link_class' => 'elgg-lightbox',
		'priority' => 999,
	);

	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
 * Teacher annotation created, create access lists
 */
function teacherannotation_create_event_listener($event, $object_type, $object) {
	// Teacherannotations valid subtypes (could be more than one down the road)
	$ta_subtypes = array(
		'ta_sticky_note',
	);
	if (in_array($object->getSubtype(), $ta_subtypes)) {
		$ta_acl = create_access_collection(elgg_echo('teacherannotations:label:private'), $object->guid);
		if ($ta_acl) {
			$object->ta_acl = $ta_acl;
			$posted_to = get_entity($object->posted_to_entity_guid);
			$context = elgg_get_context();
			elgg_set_context('ta_acl');
			add_user_to_access_collection($object->owner_guid, $ta_acl);    // Add note owner
			add_user_to_access_collection($posted_to->owner_guid, $ta_acl); // Add entity owner
			elgg_set_context($context);
			if ($object->access_id == ACCESS_TA_PRIVATE) {
				$object->access_id = $ta_acl;
				$object->save();
			}
		} else {
			return false;
		}
	}
	return true;
}

/**
 * Teacher annotation deleted, remove access lists.
 */
function teacherannotation_delete_event_listener($event, $object_type, $object) {
	$ta_subtypes = array(
		'ta_sticky_note',
	);
	if (in_array($object->getSubtype(), $ta_subtypes)) {
		$context = elgg_get_context();
		elgg_set_context('ta_acl');
		delete_access_collection($object->ta_acl);
		elgg_set_context($context);
	}
	return true;
}