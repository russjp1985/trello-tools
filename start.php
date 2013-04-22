<?php
require './lib/autoload.php';

$opts = getopt('b:t:c:');
$board_id = $opts['b'];
$config_path = $opts['c'];
$to = $opts['t'];
$tz = $tops['z'];

// Default the timezone to US
if (empty($tz)) {
    $tz = 'America/New_York';
}
date_default_timezone_set('America/New_York');

$config = json_decode(file_get_contents($config_path), true);

$end = strtotime('1 week ago');
$start = time();

Trello::connect($config['auth']);

$board = new Trello_Board($board_id);
$board->sortActionsByMember();
$board->summarize();

ob_start();
include "email_template.php";
$body = ob_get_clean();

//echo "Sending email '{$board->data['name']}: {$board->summary}' to {$to}\n";
echo $body;

