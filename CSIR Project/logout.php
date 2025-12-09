<?php
require_once 'session.php';
logoutUser();
header('Location: login.php');
exit();
