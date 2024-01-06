<?php
// ... (unchanged code)

/**
 * CLI update for moodec enrolments expiration.
 *
 * Notes:
 *   - it is required to use the web server account when executing PHP CLI scripts
 *   - you need to change the "www-data" to match the apache user account
 *   - use "su" if "sudo" not available
 *
 * @package    enrol_moodec
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('CLI_SCRIPT', true);

require __DIR__ . '/../../../config.php';
require_once "$CFG->libdir/clilib.php";

// Now get cli options.
[$options, $unrecognized] = cli_get_params(['verbose' => false, 'help' => false], ['v' => 'verbose', 'h' => 'help']);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['help']) {
    $help = <<<EOT
Execute moodec enrolments expiration sync and send notifications.

Options:
-v, --verbose         Print verbose progress information
-h, --help            Print out this help

Example:
\$ sudo -u www-data /usr/bin/php enrol/self/moodec/sync.php
EOT;

    echo $help;
    die;
}

if (!enrol_is_enabled('moodec')) {
    cli_error('enrol_moodec plugin is disabled, synchronization stopped', 2);
}

$trace = empty($options['verbose']) ? new null_progress_trace() : new text_progress_trace();

/** @var $plugin enrol_moodec_plugin */
$plugin = enrol_get_plugin('moodec');

$result = $plugin->sync($trace, null);
$plugin->send_expiry_notifications($trace);

exit($result);
```

Please note that these changes are mainly stylistic and geared towards making the code more modern. Depending on your specific environment and requirements, you may need to make additional adjustments. Always ensure you thoroughly test your code after making updates.
