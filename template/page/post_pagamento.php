<?php
    /* Recupero dati transazione */
    $id_transazione = $_POST['txn_id'];
    $token_ordine = $_POST['custom'];
    $prezzo_ordine = $_POST['mc_gross'];
    $commissioni_ordine = $_POST['mc_fee'];

    /* Recupero ordine ID e cliente ID da token recuperato */
    $recupero_id_ordine_cliente_da_token_post_paypal__query = "SELECT * FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
    $recupero_id_ordine_cliente_da_token_post_paypal__execute = $istanzaConnessioneDatabase->query($recupero_id_ordine_cliente_da_token_post_paypal__query);
    $recupero_id_ordine_cliente_da_token_post_paypal__datum = $recupero_id_ordine_cliente_da_token_post_paypal__execute->fetch_assoc();
    $id_ordine = $recupero_id_ordine_cliente_da_token_post_paypal__datum['pKey_OrdineId'];
    $id_cliente = $recupero_id_ordine_cliente_da_token_post_paypal__datum['fKey_ClienteRif'];

    /* Recupero info cliente per invio ricevuta di pagamento */
    $recupero_info_cliente__query = "SELECT * FROM tabella_clienti WHERE pKey_ClienteId = '$id_cliente'";
    $recupero_info_cliente__execute = $istanzaConnessioneDatabase->query($recupero_info_cliente__query);
    $recupero_info_cliente__datum = $recupero_info_cliente__execute->fetch_assoc();
    $email_cliente = $recupero_info_cliente__datum['desc_ClienteEmail'];
    $nome_cliente = $recupero_info_cliente__datum['desc_ClienteNome'];

    /* Definizione data pagamento */
    $data_pagamento = date('Y-m-d H:i:s');

    /* Inserimento transazione nel database */
    $registrazione_transazione_nel_database__query = "INSERT INTO tabella_transazioni(pKey_TransazioneId, fKey_OrdineRif, desc_TransazioneData, desc_TransazionePrezzo, desc_TransazioneTariffa) VALUES('$id_transazione', '$id_ordine', '$data_pagamento', '$prezzo_ordine', '$commissioni_ordine')";
    if($registrazione_transazione_nel_database__execute = $istanzaConnessioneDatabase->query($registrazione_transazione_nel_database__query)) {
        echo 'Pagamento avvenuto con successo. Reindirizzamento in corso...';

        /* Preparazione per invio ricevuta di pagamento al cliente */
        $email_mittente = 'info@stimaterreni.it';

        $email_destinatario = $email_cliente;

        $messaggio_email = 'Gentile <b>' . $nome_cliente . '</b>,<br>';
        $messaggio_email .= 'grazie per il tuo pagamento. Di seguito i dettagli della transazione:<br><br>';
        $messaggio_email .= 'ID transazione: <b>' . $id_transazione . '</b><br>';
        $messaggio_email .= 'Importo transazione: euro <b>' . numeroFormattato($prezzo_ordine) . '</b><br>';
        $messaggio_email .= 'Data transazione: <b>' . $data_pagamento . '</b><br>';
        $messaggio_email .= 'ID ordine: <b>' . $id_ordine . '</b> (token <b>' . $token_ordine . '</b>)<br><br>';
        $messaggio_email .= 'Grazie per aver scelto il nostro servizio.<br>Il team di stimaterreni.it';
        
        $oggetto_email = 'Ricevuta di pagamento per ordine numero ' . $id_ordine;

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Stimaterreni.it <' . $email_mittente . '>' . "\r\n";

        /* Invio email */
        mail($email_destinatario, $oggetto_email, $messaggio_email, $headers);

        header('location:https://www.stimaterreni.it/index.php?page=evasione_ordine&token_ordine=' . $token_ordine);
    }
?>