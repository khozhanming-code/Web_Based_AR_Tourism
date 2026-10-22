<?php require_once __DIR__.'/../includes/functions.php'; if(!empty($_SESSION['admin_id']))log_activity('logout','Administrator logged out'); $_SESSION=[]; session_destroy(); redirect('login.php');
