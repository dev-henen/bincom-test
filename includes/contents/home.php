<?php 

$template = new template\Loader('home');
$template->show_errors = true;
$template->show_warnings = true;

$template->render();