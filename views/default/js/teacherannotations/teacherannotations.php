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
?>
//<script>
elgg.provide('elgg.teacherannotations');

// Init function
elgg.teacherannotations.init = function() {
	$("div#ta-bottom-bar").appendTo('body').css({height: 0}).animate({ height: '18' }, 'slow');

	// Fix broken youtube embed
	$('iframe').each(function() {
		var url = $(this).attr("src");
		$(this).attr("src",url+"?wmode=transparent");
	});
}

elgg.register_hook_handler('init', 'system', elgg.teacherannotations.init);
//</script>