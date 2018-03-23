<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of jitsi
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_jitsi
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace jitsi with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once('../../config.php');


global $USER;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... jitsi instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('jitsi', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $jitsi  = $DB->get_record('jitsi', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $jitsi  = $DB->get_record('jitsi', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $jitsi->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('jitsi', $jitsi->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_jitsi\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $jitsi);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/jitsi/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($jitsi->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('jitsi-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

echo $OUTPUT->heading($jitsi->name);


// Conditions to show the intro can change to look for own settings or whatever.
if ($jitsi->intro) {
    echo $OUTPUT->box(format_module_intro('jitsi', $jitsi, $cm->id), 'generalbox mod_introbox', 'jitsiintro');
}


$nom=$USER->username;
$avatar=$CFG->wwwroot.'/user/pix.php/'.$USER->id.'/f1.jpg';

$urlparams = array('avatar' => $avatar,'nom' => $USER->username, 'ses'=>$course->shortname."-".$jitsi->name);

$today = getdate();

if ($today[0]>($jitsi->timeopen-1800)){
  echo $OUTPUT->box(get_string('instruction', 'jitsi'));

  echo $OUTPUT->single_button(new moodle_url('/mod/jitsi/sesion.php', $urlparams), get_string('access', 'jitsi'), 'post');
}else{
  echo $OUTPUT->box(get_string('nostart', 'jitsi'));
}


// Finish the page.
echo $OUTPUT->footer();
