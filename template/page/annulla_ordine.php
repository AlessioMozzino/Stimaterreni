<?php
    /* Recupero token da url per annullamento ordine */
    $esistenza_token_url = isset($_GET['token_ordine']);
    if($esistenza_token_url) {
        $token_ordine = $_GET['token_ordine'];

        /* Verifica esistenza token */
        $esistenza_token_db__query = "SELECT COUNT(desc_OrdineToken) FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
        $esistenza_token_db__execute = $istanzaConnessioneDatabase->query($esistenza_token_db__query);
        $esistenza_token_db__datum = $esistenza_token_db__execute->fetch_assoc();
        $count_token_db = $esistenza_token_db__datum['COUNT(desc_OrdineToken)'];

        if($count_token_db > 0) {
            /* Procedura di recupero ID ordine da token */
            $recupero_id_ordine_da_token_url__query = "SELECT pKey_OrdineId FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
            $recupero_id_ordine_da_token_url__execute = $istanzaConnessioneDatabase->query($recupero_id_ordine_da_token_url__query);
            $recupero_id_ordine_da_token_url__datum = $recupero_id_ordine_da_token_url__execute->fetch_assoc();
            $id_ordine_da_token_url = $recupero_id_ordine_da_token_url__datum['pKey_OrdineId'];
        
            /* Procedura di annullamento ordine */
            $annullamento_ordine__query = "UPDATE tabella_ordini SET fKey_OrdineStato = 'KO' WHERE pKey_OrdineId = '$id_ordine_da_token_url'";
            $annullamento_ordine__execute = $istanzaConnessioneDatabase->query($annullamento_ordine__query);
        }
    }
?>

<div class="sub-container-100">
    <h1>L'ordine è stato annullato.</h1>
    <p>
        Ci dispiace che il servizio non abbia soddisfatto le tue aspettative. Facci sapere cosa è andato storto direttamente dalla sezione "contattaci".
    </p>
</div>