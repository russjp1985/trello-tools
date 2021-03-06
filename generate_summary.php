<?php
require './lib/autoload.php';

$opts = getopt('b:t:c:');
$board_id = $opts['b'];
$config_path = $opts['c'];
$tz = $tops['z'];

// Default the timezone to US
if (empty($tz)) {
    $tz = 'America/New_York';
}
date_default_timezone_set('America/New_York');

if (empty($config_path) || !file_exists($config_path)) {
    echo "You must provide a valid config path!\n";
    exit(1);
}

if (empty($board_id)) {
    echo "You must provide a board id\n";
    exit(1);
}

$config = json_decode(file_get_contents($config_path), true);

$end = strtotime('1 week ago');
$start = time();

Trello::connect($config['auth']);

$board = new Trello_Board($board_id);
$board->sortActionsByMember($start, $end);
$board->summarize($start, $end);

ob_start();
include "./templates/summary.php";
$body = ob_get_clean();

//echo "Sending email '{$board->data['name']}: {$board->summary}' to {$to}\n";
echo $body;

