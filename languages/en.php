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
	'teacherannotations:label:unresolve' => 'Unresolve',
	'teacherannotations:label:cancel' => 'Cancel',
	'teacherannotations:label:comment' => 'Add Comment',
	'teacherannotations:label:save' => 'Save',
	'teacherannotations:label:stickynotes' => 'Sticky Notes',
	'teacherannotations:label:add' => 'Add',
	'teacherannotations:label:show' => 'Show',
	'teacherannotations:label:hide' => 'Hide',
	'teacherannotations:label:all' => 'All',
	'teacherannotations:label:unresolved' => 'Unresolved',
	'teacherannotations:label:access' => 'Access',
	'teacherannotations:label:accessprivate' => 'Private (Note and page owner)',
	'teacherannotations:label:accessloggedin' => 'Logged In Users',
	'teacherannotations:label:private' => 'Private Annotation',
	'teacherannotations:label:entitystickied' => 'This item has sticky notes',
	'teacherannotations:label:noteaddedby' => 'Added by %s %s',

	// River

	// Messages
	'teacherannotations:error:description' => 'Missing sticky note text',
	'teacherannotations:error:savestickynote' => 'There was an error saving the sticky note',
	'teacherannotations:error:invalidstickynote' => 'Invalid sticky note',
	'teacherannotations:error:deletesticky' => 'There was an error deleting the sticky note',
	'teacherannotations:error:invalidsticky' => 'Invalid sticky note',
	'teacherannotations:error:comment' => 'There was an error adding the comment',
	'teacherannotations:error:commentblank' => 'Missing comment text',
	'teacherannotations:error:resolvesticky' => 'There was an error updating the stickies resolved status',
	'teacherannotations:error:entity' => 'Missing entity',
	'teacherannotations:success:deletesticky' => 'Sticky note deleted!',
	'teacherannotations:success:savestickynote' => 'Sticky note saved!',
	'teacherannotations:success:resolvesticky' => 'Sticky note marked as resolved',
	'teacherannotations:success:unresolvesticky' => 'Sticky note unmarked as resolved',
	'teacherannotations:success:comment' => 'Comment Added!',

	// Notifications
	'teacherannotations:notification:stickynotecreate:subject' => 'Sticky Note Notification',
	'teacherannotations:notification:stickynotecreate:body' => '%s has posted a sticky note on your Spot content titled:

%s

It reads:

%s

To view your item, click here:

%s',

	'teacherannotations:notification:stickynotecomment:subject' => 'Sticky Note Commented',
	'teacherannotations:notification:stickynotecomment:body' => '%s has commented on your sticky note. It reads:

%s

To view your sticky note, click here:

%s',

	// Other content
);

add_translation('en',$english);
