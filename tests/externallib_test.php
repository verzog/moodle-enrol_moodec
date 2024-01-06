<?php
// ... (header comments)

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once $CFG->dirroot . '/webservice/tests/helpers.php';
require_once $CFG->dirroot . '/enrol/moodec/externallib.php';

class enrol_moodec_externallib_testcase extends externallib_advanced_testcase {

    /**
     * Test get_enrolled_users
     */
    public function test_enrol_users(): void {
        global $DB;

        $this->resetAfterTest(true);

        $user = self::getDataGenerator()->create_user();
        $this->setUser($user);

        $course1 = self::getDataGenerator()->create_course();
        $course2 = self::getDataGenerator()->create_course();
        $user1 = self::getDataGenerator()->create_user();
        $user2 = self::getDataGenerator()->create_user();

        $context1 = context_course::instance($course1->id);
        $context2 = context_course::instance($course2->id);
        $instance1 = $DB->get_record('enrol', array('courseid' => $course1->id, 'enrol' => 'moodec'), '*', MUST_EXIST);
        $instance2 = $DB->get_record('enrol', array('courseid' => $course2->id, 'enrol' => 'moodec'), '*', MUST_EXIST);

        // Set the required capabilities by the external function.
        $roleid = $this->assignUserCapability('enrol/moodec:enrol', $context1->id);
        $this->assignUserCapability('moodle/course:view', $context1->id, $roleid);
        $this->assignUserCapability('moodle/role:assign', $context1->id, $roleid);
        $this->assignUserCapability('enrol/moodec:enrol', $context2->id, $roleid);
        $this->assignUserCapability('moodle/course:view', $context2->id, $roleid);
        $this->assignUserCapability('moodle/role:assign', $context2->id, $roleid);

        allow_assign($roleid, 3);

        // Call the external function.
        enrol_moodec_external::enrol_users([
            ['roleid' => 3, 'userid' => $user1->id, 'courseid' => $course1->id],
            ['roleid' => 3, 'userid' => $user2->id, 'courseid' => $course1->id],
        ]);

        $this->assertEquals(2, $DB->count_records('user_enrolments', ['enrolid' => $instance1->id]));
        $this->assertEquals(0, $DB->count_records('user_enrolments', ['enrolid' => $instance2->id]));
        $this->assertTrue(is_enrolled($context1, $user1));
        $this->assertTrue(is_enrolled($context1, $user2));

        // ... (remaining code)
    }
}
