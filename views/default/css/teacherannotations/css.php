<?php
/**
 * Teacher Annotations CSS
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */
?>

@media screen {
	body > div#ta-bottom-bar {
		position: fixed;
	}
}

div#ta-bottom-bar {
    background-color: #424242;
    box-shadow: 0px 0px 6px #333;
    bottom: 0;
    color: #000000;
    font-weight: bold;
    height: 0;
    left: 50%;
	margin-left: -179px; /* Half the width for horizontal centering */
    overflow: hidden;
    padding: 5px 17px 10px;
    position: absolute;
    width: auto;
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

.elgg-menu-teacherannotations li a {
    vertical-align: middle !important; 
}

.elgg-menu-teacherannotations li a span.elgg-icon {
    margin-top: 2px;
    margin-left: 10px;
}

.elgg-menu-teacherannotations li span {
    vertical-align: top !important; 
}