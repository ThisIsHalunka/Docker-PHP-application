<?php

require_once 'ZendeskAPI.php';
require_once 'ZendeskTicketExporter.php';

$subdomain = 'halunka';
$email = 'thisishalunka@gmail.com';
$token = 'PdYbh8qQ3JP3WminxCHV0pXufGZKQ1X7GmE6gYBE';

$api = new ZendeskAPI($subdomain, $email, $token);
$exporter = new ZendeskTicketExporter($api);

$exporter->exportTicketsToCsv('tickets.csv');