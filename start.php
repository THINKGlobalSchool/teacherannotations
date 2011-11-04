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
 */

elgg_register_event_handler('init', 'system', 'teacher_annotations_init');

function teacher_annotations_init() {

	// Register and load library
	elgg_register_library('elgg:teacherannotations', elgg_get_plugins_path() . 'teacherannotations/lib/teacherannotations.php');
	elgg_load_library('elgg:teacherannotations');

	// Register CSS
	$t_css = elgg_get_simplecache_url('css', 'teacherannotations/css');
	elgg_register_simplecache_view('css/teacherannotations/css');
	elgg_register_css('elgg.teacherannotations', $t_css);
	elgg_load_css('elgg.teacherannotations');

	// Register JS libraries
	$t_js = elgg_get_simplecache_url('js', 'teacherannotations/teacherannotations');
	elgg_register_simplecache_view('js/teacherannotations/teacherannotations');
	elgg_register_js('elgg.teacherannotations', $t_js);
	elgg_load_js('elgg.teacherannotations');

	// Register page handler
	elgg_register_page_handler('teacherannotations','teacherannotations_page_handler');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'teacherannotations/actions/teacherannotations';
	//elgg_register_action('teacherannotations/save', "$action_base/save.php");

	return TRUE;
}

/**
 * Teacher annotations page handler
 */
function teacherannotations_page_handler($page) {
	echo 'Test';
}