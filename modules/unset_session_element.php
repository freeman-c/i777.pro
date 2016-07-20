<?php
session_start();
unset($_SESSION['user'][$_POST['element']]);
?>