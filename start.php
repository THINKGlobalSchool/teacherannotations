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
 * - Figure out permissions for sticky notes
 * - Object relationship for sticky notes
 * - How big do we want these notes to get? Limit characters?
 * - What should happen when we resolve a note?
 * - Annotation notifications
 * - River?
 */

elgg_register_event_handler('init', 'system', 'teacher_annotations_init');

function teacher_annotations_init() {

	// Define color constants
	define("TA_COLOR_YELLOW", 'yellow');
	define("TA_COLOR_BLUE", 'blue');
	define("TA_COLOR_GREEN", 'green');
	define("TA_COLOR_ORANGE", 'orange');
	define("TA_COLOR_PURPLE", 'purple');

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
	elgg_load_js('elgg.teacherannotations');

	$ts_js = elgg_get_simplecache_url('js', 'teacherannotations/stickynotes');
	elgg_register_simplecache_view('js/teacherannotations/stickynotes');
	elgg_register_js('elgg.teacherannotations.stickynotes', $ts_js);
	elgg_load_js('elgg.teacherannotations.stickynotes');
	
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
			echo "GUID: $note->guid<br />";
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
 */
function teacherannotations_entity_full_view_handler($hook, $type, $result, $params) {
	if (elgg_get_viewtype() != "default") {
		return;
	}

	if ($params['view'] == 'object/todo' || $params['vars']['full_view']) {
		error_log((int)$params['vars']['full_view'] . ' ' . $params['view']);
	}

	// Only dealing with straight up object views here
	if (strpos($params['view'], 'object/') === 0              // Check that view is an object view
		&& strpos($params['view'], 'object/elements') !== 0   // Ignore object/elements views
		&& $params['vars']['full_view']) {                    // Check for full view

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

		$result .= "<div class='ta-sticky-notes-extenstion'></div>";
	}
	return $result;
}