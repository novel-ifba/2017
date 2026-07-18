<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['smtp_host'] = getenv('SMTP_HOST') ?: 'ssl://smtp.gmail.com';
$config['smtp_port'] = getenv('SMTP_PORT') ?: 465;
$config['smtp_user'] = getenv('SMTP_USER') ?: 'novel.noreply@gmail.com';
$config['smtp_pass'] = getenv('SMTP_PASS') !== FALSE ? getenv('SMTP_PASS') : 'aprendizagemautonoma';
$config['protocol']  = getenv('SMTP_PROTOCOL') ?: 'smtp';
$config['validate']  = TRUE;
$config['mailtype']  = 'html';
$config['charset']   = 'utf-8';
$config['newline']   = "\r\n";
