<?php

function dd($val) {
	echo "<pre>";
	var_dump($val);
	echo "</pre>";
	die();
}

function dp($val) {
	echo "<pre>";
	print_r($val);
	echo "</pre>";
	die();
}