<?php
    /* Dati */
    $nomeHost = 'stimaterreni.it';
    $nomeUtente = 'kpqiqvby_alessiomozzino';
    $passwordAccesso = 'lrCS.z}ZA^#k^N@-(bT7m_-~P';
    $nomeDatabase = 'kpqiqvby_stimaterreni';

    /* Connessione al database */
    $istanzaConnessioneDatabase = new mysqli($nomeHost, $nomeUtente, $passwordAccesso, $nomeDatabase);
    if ($istanzaConnessioneDatabase->connect_error) {
        die('Errore di connessione (' . $istanzaConnessioneDatabase->connect_errno . ') '. $istanzaConnessioneDatabase->connect_error);
    }

    /* Impostazione codifica caratteri */
    $codificaCaratteriUTF8 = "SET NAMES utf8";
    $istanzaConnessioneDatabase->query($codificaCaratteriUTF8);
?>
