<?php
/**
 * Teacher Annotations English Translation
 *
 * @package TeacherAnnotations
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$english = array(
	// Generic
	'item:object:teacherannotation' => 'Teacher Annotation',
	'item:object:ta_sticky_note' => 'Sticky Notes',

	// Page titles 

	// Labels
	'teacherannotations:label:description' => 'Text of the note',
	'teacherannotations:label:color' => 'Color',
	'teacherannotations:label:edit' => 'Edit',
	'teacherannotations:label:delete' => 'Delete',
	'teacherannotations:label:resolve' => 'Resolve',
	'teacherannotations:label:cancel' => 'Cancel',
	'teacherannotations:label:comment' => 'Add Comment',
	'teacherannotations:label:save' => 'Save',
	'teacherannotations:label:stickynotes' => 'Sticky Notes',
	'teacherannotations:label:add' => 'Add',

	// River

	// Messages
	'teacherannotations:error:description' => 'Missing sticky note text',
	'teacherannotations:error:savestickynote' => 'There was an error saving the sticky note',
	'teacherannotations:error:invalidstickynote' => 'Invalid sticky note',
	'teacherannotations:error:deletesticky' => 'There was an error deleting the sticky note',
	'teacherannotations:error:invalidsticky' => 'Invalid sticky note',
	'teacherannotations:error:comment' => 'There was an error adding the comment',
	'teacherannotations:error:commentblank' => 'Missing comment text',
	'teacherannotations:error:resolvesticky' => 'There was an error marking the sticky note as resolved',
	'teacherannotations:success:deletesticky' => 'Sticky note deleted!',
	'teacherannotations:success:savestickynote' => 'Sticky note saved!',
	'teacherannotations:success:resolvesticky' => 'Sticky note marked as resolved',
	'teacherannotations:success:comment' => 'Comment Added!',

	// Other content
);

add_translation('en',$english);
