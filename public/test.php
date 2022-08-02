<?php
$token = '1462715989:AAFo3lN_kgAst-hb3E-AjczZ72hlH7D24k0';      //сюда вставляем токен полученый от botFather
$recipient = '333166342';

//$text = implode('%0A',$_POST);
$text .= 'Заявка ' . '%0A';
$text .= "Имя: " . $_POST['clientName'] . '%0A';
$text .= "Email: " . $_POST['clientEmail'] . '%0A';
$text .= "Телефон: " . $_POST['clientPhone'] . '%0A';


file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$recipient&text=$text", False);
