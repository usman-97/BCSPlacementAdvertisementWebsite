<?php
require_once ("Models/Skills.php");

$view = new stdClass();
$view->pageTitle = "Create Placement";
$skills = new Skills();

require_once ("logout.php");

$view->getAllSkills = $skills->listPlacementSkills();

require_once ("Views/createPlacement.phtml");