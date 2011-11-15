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
	overflow: hidden;
	width: 50%;
	height: 0px;
	position: absolute;
	bottom: 0;
	left: 25%;
	color: #000;
	font-weight: bold;
	background-color: #BBBBBB;
	box-shadow: 0px 0px 3px #333;
	-webkit-border-radius: 10px 10px 0 0;
	-moz-border-radius: 10px 10px 0 0;
	border-radius: 10px 10px 0 0;
	padding: 5px;
}

#ta-bottom-bar ul li a:hover {
	color: #000033;
}