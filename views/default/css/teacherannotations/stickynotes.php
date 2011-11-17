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
	min-height: 165px;
	padding: 10px;
	width: 165px;
	position: absolute;
	overflow: hidden;
	cursor: move;
	-moz-box-shadow: 1px 1px 3px #666;
	-webkit-box-shadow: 1px 1px 3px #666;
	box-shadow: 1px 1px 3px #666;
}

#ta-sticky-notes-container {
	position: absolute;
	overflow: visible;
	height: 1px;
	width: 1px;
}

#ta-sticky-notes-boundary {
	position: absolute;
	overflow: visible;
	z-index: -1;
}

#ta-sticky-note-preview {
	cursor: default;
}

.ta-sticky-note-color {
	cursor: pointer;
	float: left;
	height: 14px;
	margin: 0 5px 0 0;
	width: 14px;
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

.ta-sticky-note.orange, .ta-sticky-note-color.orange {
	background: #FFA54C;
	border: 1px solid #f28e50;
}

.ta-sticky-note.purple, .ta-sticky-note-color.purple {
	background: #eaafe3;
	border: 1px solid #BAACA2;
}


.ta-sticky-note span.ta-sticky-note-guid {
	display:none;
}

#ta-sticky-note-add {
	float: right;
}

#ta-sticky-note-add-form {
	height: 240px;
	margin: 10px 0 0 200px;
	width: 350px;
}

.ta-sticky-note-author {
	color: #333;
	font-size: 11px;
}

.ta-sticky-note-author .elgg-avatar {
	float: left;
}

.ta-sticky-note-author .ta-sticky-note-author-info {
	float: left;
	padding-left: 4px;
	margin-top: -2px;
}

.ta-sticky-note-author .elgg-subtext {
	color: inherit;
	display: block;
	margin-top: -2px;
}

.ta-sticky-note-actions {
	font-size: 10px;
}

.ta-sticky-note-body {
	font-size: 105%;
}

.ta-sticky-note-edit-container {

}

.ta-sticky-note-edit-body {
	height: 100px;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
	padding: 2px;
	border: 1px solid #666;
	background: rgba(255, 255, 255, 0.6);
}

.ta-sticky-note-edit-color {
	float: right;
	background: #FFFFFF;
	padding: 3px 0 3px 3px;
	margin-top: 1px;
	margin-bottom: 10px;
	border: 1px solid #AAA;
}

.ta-sticky-note-edit-color .ta-sticky-note-color {
	margin-right: 3px;
}

.ta-sticky-notes-edit-submit-button {
	height: 22px;
	font-size: 11px;
	padding: 0 4px;
}

.ta-sticky-notes-comment-submit-button {
	margin-bottom: 10px;
}

.ta-sticky-note-comments-container {
	font-size: 11px;
}

.ta-sticky-note-comment-list {
	margin-top: 10px;
}

.ta-sticky-note-comment-list .elgg-list, .ta-sticky-note-comment-list .elgg-list > li {
	border-top: 0 none;
	border-bottom: 0 none;
}

.ta-sticky-note-comment-list h3 {
	border-top: 1px dotted #999;
	padding-top: 3px;
}

.ta-sticky-note-comment-list .elgg-output {
	margin-top: 3px;
}

.ta-sticky-note-comment-list .elgg-subtext {
	margin-bottom: 3px;
	color: inherit;
	display: block;
	margin-top: -2px;
}

.ta-sticky-note-comment, .ta-sticky-note-comment-cancel {
	float: right;
	margin-bottom: 5px;
}

#ta-add-sticky-note-form {
	padding: 3px;
	height: auto;
	width: auto;
}

#ta-add-sticky-note-form label {
	display: block;
	font-weight: bold;
	padding-bottom: 3px;
}

#ta-add-sticky-note-form textarea, .note-form input[type=text]{
	background-color: #FCFCFC;
	border: 1px solid #AAAAAA;
	font-size: 16px;
	height: 60px;
	padding: 5px;
	width: 300px;
	margin-bottom: 10px;
}

#ta-add-sticky-note-form input[type=text] {
	height: auto;
}

.ta-sticky-note-access-display {
	position: absolute;
	bottom: 0px;
	right: 10px;
	font-size: 10px;
	color: #444;
}

.ta-sticky-note-access-edit-dropdown {
	width: 100%;
	margin-bottom: 2px;
}

/* Resolved */
.ta-sticky-note-resolved {
	display: none;
}

/* Stickynote menu */
li.elgg-menu-item-ta-sticky-note-add:after,
li.elgg-menu-item-ta-sticky-notes-hide-all:after,
li.elgg-menu-item-ta-sticky-notes-show-all:after,
li.elgg-menu-item-ta-sticky-notes-show-unresolved:after {
	content: "\00a0|\00a0"; // Add a pipe between menu items
}


/* Resizeable stuff */
.ta-sticky-note .ui-icon {
	width: 16px;
	height: 16px;
	background-image: url(<?php echo elgg_get_site_url(); ?>mod/teacherannotations/graphics/ui-icons-ta.png);
}
.ta-sticky-note .ui-icon-gripsmall-diagonal-se { background-position: -64px -224px; }
.ta-sticky-note .ui-resizable-handle { position: absolute;font-size: 0.1px;z-index: inherit !important; display: block;}
.ta-sticky-note .ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }
.ta-sticky-note .ui-resizable-n { cursor: n-resize; height: 7px; width: 100%; top: -5px; left: 0px; }
.ta-sticky-note .ui-resizable-s { cursor: s-resize; height: 7px; width: 100%; bottom: -5px; left: 0px; }
.ta-sticky-note .ui-resizable-e { cursor: e-resize; width: 12px; right: -5px; top: 0px; height: 100%; }
.ta-sticky-note .ui-resizable-w { cursor: w-resize; width: 7px; left: -5px; top: 0px; height: 100%; }
.ta-sticky-note .ui-resizable-se { cursor: se-resize; width: 12px; height: 12px; right: 1px; bottom: 1px; }
.ta-sticky-note .ui-resizable-sw { cursor: sw-resize; width: 9px; height: 9px; left: -5px; bottom: -5px; }
.ta-sticky-note .ui-resizable-nw { cursor: nw-resize; width: 9px; height: 9px; left: -5px; top: -5px; }
.ta-sticky-note .ui-resizable-ne { cursor: ne-resize; width: 9px; height: 9px; right: -5px; top: -5px;}/* Accordion
