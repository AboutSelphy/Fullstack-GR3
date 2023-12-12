<?php
require_once('../../script/db_connection.php');
require_once('./script/unsetSessionCookie.php');

//unset our beautiful cookie :/
unsetSessionCookie($db);

include_once('../../script/loginGate.php');