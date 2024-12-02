<?php

ob_start(); 
# Запуск сесій
@session_name('SID');
@session_start();

$sessID = addslashes(session_id());

if (!preg_match('#[A-z0-9]{32}#i', $sessID)) {
  $sessID = md5(mt_rand(000000, 999999));
}

