<?php
require "./tests/autoload.php";
$opts = getopt('', array('verbose:'));

new TrelloTest($opts);

