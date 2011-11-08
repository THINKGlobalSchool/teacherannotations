<?php
/**
 * Teacher Annotations Stickynotes CSS
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
#popup-sticky-form {
	display: none;
}

.ta-sticky-note {
	height: 150px;
	padding: 10px;
	width: 150px;
	position: absolute;
	overflow: hidden;
	cursor: move;
	font-size: 16px;
	line-height: 20px;
	-moz-box-shadow: 2px 2px 0 #DDDDDD;
	-webkit-box-shadow: 2px 2px 0 #DDDDDD;
	box-shadow: 2px 2px 0 #DDDDDD;
}

#ta-sticky-note-preview {
	cursor: default;
}

.ta-sticky-note.yellow, .ta-sticky-note-color.yellow {
	background-color: #FDFB8C;
	border: 1px solid #DEDC65;
}

.ta-sticky-note.blue, .ta-sticky-note-color.blue {
	background-color: #A6E3FC;
	border: 1px solid #75C5E7;
}

.ta-sticky-note.green, .ta-sticky-note-color.green {
	background-color: #A5F88B;
	border: 1px solid #98E775;
}

.ta-sticky-note span.data { 
	display:none;
}

#ta-sticky-note-add {
	/*position:absolute;
	top:-70px;
	left:0;
	*/
	float: right;
}

.ta-sticky-note-author {
	bottom: 5px;
	color: #666666;
	font-size: 12px;
	position: absolute;
	right: 10px;
}

#ta-sticky-notes-main {
	/* Contains all the notes and limits their movement: */
	margin: 0 auto;
	position:relative;
	width: 100%;
	height: 100%;
	z-index: 10;
	background:url(img/add_a_note_help.gif) no-repeat left top;
}

#ta-sticky-note-data {
	/* The input form in the pop-up: */
	height: 200px;
	margin: 10px 0 0 200px;
	width: 350px;
}

#ta-add-sticky-form {
	padding: 3px;
	height: auto;
	width: auto;
}

#ta-add-sticky-form label {
	display: block;
	font-weight: bold;
	padding-bottom: 3px;
}

#ta-add-sticky-form textarea, .note-form input[type=text]{
	background-color: #FCFCFC;
	border: 1px solid #AAAAAA;
	font-size: 16px;
	height: 60px;
	padding: 5px;
	width: 300px;
	margin-bottom: 10px;
}

#ta-add-sticky-form input[type=text] {
	height: auto;
}

.ta-sticky-note-color {
	cursor: pointer;
	float: left;
	height: 14px;
	margin: 0 5px 0 0;
	width: 14px;
}