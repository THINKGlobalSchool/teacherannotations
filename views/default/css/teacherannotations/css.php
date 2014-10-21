<?php
/**
 * Teacher Annotations CSS
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>

@media screen {
	body > div#ta-bottom-bar {
		position: fixed;
	}
}

div#ta-bottom-bar {
	background: url(<?php echo elgg_get_site_url(); ?>mod/tgstheme/_graphics/back-top.jpg) repeat-x top left #85161D;
	border-color: #000000;
    border-radius: 6px 6px 0 0;
    border-style: solid;
    border-width: 2px 2px 0;
    bottom: 0;
    color: #000000;
    font-weight: bold;
    height: 0;
    left: 50%;
	margin-left: -179px; /* Half the width for horizontal centering */
    overflow: hidden;
    padding: 0 10px 4px;
    position: absolute;
    width: 410px;
    z-index: 5000;
}

#ta-bottom-bar ul {
	color: #ffffff;
}

#ta-bottom-bar ul li {
}

#ta-bottom-bar ul li a:hover {
	color: #CCC;
	text-decoration: none;
}