<?php
include('RealmStatus.php');

$server = 'Eitrigg';
$api = new RealmStatus();

echo 'Information for '.$server."\n";
echo 'Type: '.$api->getServerType($server)."\n";
echo 'Status: '.$api->getServerStatus($server)."\n";
echo 'Population: '.$api->getServerPopulation($server)."\n";
echo 'Locale: '.$api->getServerLocale($server)."\n";

/*
 * To return an associative array containing every Server Name, 
 * Status, Type, Population, and Locale, uncomment the below line. 
 */
//print_r($api->getAllServers());

/**
 * The above should produce something similar to:
 *
 * Information for Eitrigg
 * Type: PvE
 * Status: up
 * Population: Medium
 * Locale: United States
 * 
 */

?>
