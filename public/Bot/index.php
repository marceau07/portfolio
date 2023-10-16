<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(21);
exec("node index.js");