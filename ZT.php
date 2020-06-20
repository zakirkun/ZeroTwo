<?php
/**
* @author Muhammad Zakir Ramadhan
* https://zakirpro.com (zakir@bekasidev.org)
* Don't Change My Name!
*/
require 'ZT.class.php';
$ZT = new ZT();
$ZT->parseCommand($argv);

function ambil_kata($start, $end, $content)
{
	$a = explode($start, $content);
	$b = explode($end, $a[1]);
	return $b[0];
}