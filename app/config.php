<?php
require_once __DIR__ . "/Core/Env.php";
Env::load(__DIR__ . "/../.env");

define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_NAME", $_ENV["DB_NAME"]);
define("DB_USER", $_ENV["DB_USER"]);
define("DB_PASS", $_ENV["DB_PASS"]);