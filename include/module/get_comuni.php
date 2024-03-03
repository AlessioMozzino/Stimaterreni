<?php
    require_once(__DIR__ . '/../db/conn.php');

    /* Elenco comuni in base alla provincia scelta */
    $ProvinciaId = $_POST['pKey_ProvinciaId'];
    $elenco_comuni__query = "SELECT pKey_ComuneId, desc_ComuneNome FROM tabella_comuni WHERE fKey_ProvinciaRif = '$ProvinciaId' AND flag_ComuneStato = 1";
    $elenco_comuni__execute = $istanzaConnessioneDatabase->query($elenco_comuni__query);
    while ($elenco_comuni__datum = $elenco_comuni__execute->fetch_assoc()) {
        echo "<option value=".$elenco_comuni__datum['pKey_ComuneId'].">".$elenco_comuni__datum['desc_ComuneNome']."</option>";
    }
?>
