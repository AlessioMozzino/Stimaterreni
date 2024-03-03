<?php
    /* Recupero valori */
    $tipologia_cliente = $_POST['tipologia_cliente'];

    if($tipologia_cliente == 'PF') {
        /* CLiente persona fisica */
        $denominazione_cliente = addslashes($_POST['nome_cognome_pf']);
        $provincia_cliente = $_POST['provincia_fiscale_pf'];
        $comune_cliente = $_POST['comune_fiscale_pf'];
        $cap_cliente = $_POST['cap_fiscale_pf'];
        $indirizzo_cliente = $_POST['indirizzo_fiscale_pf'];
        $civico_cliente = $_POST['civico_indirizzo_fiscale_pf'];
        $email_cliente = $_POST['indirizzo_email_pf'];
    } else if($tipologia_cliente == 'PFIVA') {
        /* CLiente persona fisica con partita IVA */
        $denominazione_cliente = addslashes($_POST['nome_cognome_pfiva']);
        $provincia_cliente = $_POST['provincia_fiscale_pfiva'];
        $comune_cliente = $_POST['comune_fiscale_pfiva'];
        $cap_cliente = $_POST['cap_fiscale_pfiva'];
        $indirizzo_cliente = $_POST['indirizzo_fiscale_pfiva'];
        $civico_cliente = $_POST['civico_indirizzo_fiscale_pfiva'];
        $email_cliente = $_POST['indirizzo_email_pfiva'];
    }else if($tipologia_cliente == 'PG') {
        /* CLiente persona giuridica */
        $denominazione_cliente = addslashes($_POST['denominazione_pg']) . ' di ' . addslashes($_POST['legale_rappresentante_pg']);
        $provincia_cliente = $_POST['provincia_fiscale_pg'];
        $comune_cliente = $_POST['comune_fiscale_pg'];
        $cap_cliente = $_POST['cap_fiscale_pg'];
        $indirizzo_cliente = $_POST['indirizzo_fiscale_pg'];
        $civico_cliente = $_POST['civico_indirizzo_fiscale_pg'];
        $email_cliente = $_POST['indirizzo_email_pg'];
    }

    /* Controllo esistenza cliente da e-mail e tipologia */
    $controllo_esistenza_cliente__query = "SELECT COUNT(desc_ClienteEmail) FROM tabella_clienti WHERE desc_ClienteEmail = '$email_cliente' AND fKey_TipClienteRif = '$tipologia_cliente'";
    $controllo_esistenza_cliente__execute = $istanzaConnessioneDatabase->query($controllo_esistenza_cliente__query);
    $controllo_esistenza_cliente__datum = $controllo_esistenza_cliente__execute->fetch_assoc();
    $controllo_esistenza_cliente = $controllo_esistenza_cliente__datum['COUNT(desc_ClienteEmail)'];

    if(!($controllo_esistenza_cliente > 0)) {
        /* Cliente non trovato - REGISTRAZIONE NUOVO CLIENTE! */
        $registrazione_nuovo_cliente__query = "INSERT INTO tabella_clienti(pKey_ClienteId, fKey_TipClienteRif, desc_ClienteNome, fKey_ClienteProvFiscRif, fKey_ClienteComFiscRif, desc_ClienteCapFisc, desc_ClienteIndFisc, desc_ClienteNumCivFisc, desc_ClienteEmail) VALUES('', '$tipologia_cliente', '$denominazione_cliente', '$provincia_cliente', '$comune_cliente', '$cap_cliente', '$indirizzo_cliente', '$civico_cliente', '$email_cliente')";
        $registrazione_nuovo_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_nuovo_cliente__query);
    }

    /* Recupero ID cliente registrato/già presente per associazione eventuale codice fiscale/partita iva/sdi/pec */
    $recupero_id_cliente__query = "SELECT pKey_ClienteId FROM tabella_clienti WHERE desc_ClienteEmail = '$email_cliente' AND fKey_TipClienteRif = '$tipologia_cliente'";
    $recupero_id_cliente__execute = $istanzaConnessioneDatabase->query($recupero_id_cliente__query);
    $recupero_id_cliente__datum = $recupero_id_cliente__execute->fetch_assoc();
    $id_cliente = $recupero_id_cliente__datum['pKey_ClienteId'];

    /* Recupero valori complementari cliente in base alla tipologia */
    if($tipologia_cliente == 'PF') {
        /* Persona fisica */
        $codice_fiscale_cliente = strtoupper(addslashes($_POST['codice_fiscale_pf']));
        /* Registrazione codice fiscale nel database */
        $registrazione_cf_cliente__query = "INSERT INTO tabella_clienti__codici_fiscali(fKey_ClienteRif, desc_ClienteCodiceFiscale) VALUES('$id_cliente', '$codice_fiscale_cliente')";
        $registrazione_cf_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_cf_cliente__query);
    } else if($tipologia_cliente == 'PFIVA') {
        /* Persona fisica con partita IVA */
        $codice_fiscale_cliente = strtoupper(addslashes($_POST['codice_fiscale_pfiva']));
        /* Registrazione codice fiscale nel database */
        $registrazione_cf_cliente__query = "INSERT INTO tabella_clienti__codici_fiscali(fKey_ClienteRif, desc_ClienteCodiceFiscale) VALUES('$id_cliente', '$codice_fiscale_cliente')";
        $registrazione_cf_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_cf_cliente__query);

        $partita_iva_cliente = $_POST['partita_iva_pfiva'];
        /* Registrazione partita IVA nel database */
        $registrazione_piva_cliente__query = "INSERT INTO tabella_clienti__partite_iva(fKey_ClienteRif, desc_ClientePartitaIva) VALUES('$id_cliente', '$partita_iva_cliente')";
        $registrazione_piva_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_piva_cliente__query);

        $pec_cliente = $_POST['indirizzo_pec_pfiva'];
        /* Registrazione PEC nel database */
        if($pec_cliente || $pec_cliente != '') {
            $registrazione_pec_cliente__query = "INSERT INTO tabella_clienti__pec(fKey_ClienteRif, desc_ClientePec) VALUES('$id_cliente', '$pec_cliente')";
            $registrazione_pec_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_pec_cliente__query);
        }

        $sdi_cliente = $_POST['codice_sdi_pfiva'];
        /* Registrazione SDI nel database */
        if($sdi_cliente || $sdi_cliente != '') { 
            $registrazione_sdi_cliente__query = "INSERT INTO tabella_clienti__sdi(fKey_ClienteRif, desc_ClienteSdi) VALUES('$id_cliente', '$sdi_cliente')";
            $registrazione_sdi_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_sdi_cliente__query);
        }
    } else if($tipologia_cliente == 'PG') {
        /* Persona giuridica */
        $partita_iva_cliente = $_POST['partita_iva_pg'];
        /* Registrazione partita IVA nel database */
        $registrazione_piva_cliente__query = "INSERT INTO tabella_clienti__partite_iva(fKey_ClienteRif, desc_ClientePartitaIva) VALUES('$id_cliente', '$partita_iva_cliente')";
        $registrazione_piva_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_piva_cliente__query);

        $pec_cliente = $_POST['indirizzo_pec_pg'];
        /* Registrazione PEC nel database */
        if($pec_cliente || $pec_cliente != '') {
            $registrazione_pec_cliente__query = "INSERT INTO tabella_clienti__pec(fKey_ClienteRif, desc_ClientePec) VALUES('$id_cliente', '$pec_cliente')";
            $registrazione_pec_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_pec_cliente__query);
        }

        $sdi_cliente = $_POST['codice_sdi_pg'];
        /* Registrazione SDI nel database */
        if($sdi_cliente || $sdi_cliente != '') { 
            $registrazione_sdi_cliente__query = "INSERT INTO tabella_clienti__sdi(fKey_ClienteRif, desc_ClienteSdi) VALUES('$id_cliente', '$sdi_cliente')";
            $registrazione_sdi_cliente__execute = $istanzaConnessioneDatabase->query($registrazione_sdi_cliente__query);
        }
    }

    /* Registrazione dati ordine + composizione (specifiche terreni) */
    // Generazione token ordine
    $token_ordine = generazioneTokenOrdine();
    // Data di ordinazione
    $data_arrivo = date('Y-m-d H:i:s');    
    // Recupero numero di terreni da valutare e calcolo importo totale ordine    
    $numero_terreni = $_POST['numero_terreni'];
    $prezzo_ordine = calcoloImportoTotaleOrdine($numero_terreni);
    // Controllo check per invio valutazioni anche via e-mail
    if(isset($_POST['flag_valutazione_email'])) { $flag_ordine_email = 1; } 
    else { $flag_ordine_email = 0; }
    // Stato ordine
    $stato_ordine = 'WORK';

    // Caricamento nel database
    $registrazione_ordine__query = "INSERT INTO tabella_ordini(pKey_OrdineId, desc_OrdineToken, fKey_ClienteRif, desc_OrdineArrivo, desc_OrdinePrezzo, flag_OrdineEmail, fKey_OrdineStato) VALUES('', '$token_ordine', '$id_cliente', '$data_arrivo', '$prezzo_ordine', '$flag_ordine_email', '$stato_ordine')";
    $registrazione_ordine__execute = $istanzaConnessioneDatabase->query($registrazione_ordine__query);

    // Recupero id ordine
    $recupero_id_ordine__query = "SELECT pKey_OrdineId FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine' AND fKey_ClienteRif = '$id_cliente'";
    $recupero_id_ordine__execute = $istanzaConnessioneDatabase->query($recupero_id_ordine__query);
    $recupero_id_ordine__datum = $recupero_id_ordine__execute->fetch_assoc();
    $id_ordine = $recupero_id_ordine__datum['pKey_OrdineId'];

    // Definizione composizione ordine
    for($count = 1; $count < $numero_terreni + 1; $count++) {
        ${'terreno' . $count} = array($id_ordine, $count, (int)$_POST['provinciaTerreno' . $count], (int)$_POST['comuneTerreno' . $count], 0, (int)$_POST['colturaTerreno' . $count], calcoloSuperficieEttariDaMetriQuadri($_POST['superficieTerreno' . $count]));

        // Recupero regione agraria per i comuni scelti
        $recupero_regione_agraria__query = "SELECT fKey_RegAgrRif FROM tabella_comuni WHERE pKey_ComuneId = " . ${'terreno' . $count}[3];
        $recupero_regione_agraria__execute = $istanzaConnessioneDatabase->query($recupero_regione_agraria__query);
        $recupero_regione_agraria__datum = $recupero_regione_agraria__execute->fetch_assoc();
        ${'regioneAgrariaTerreno' . $count} = $recupero_regione_agraria__datum['fKey_RegAgrRif'];

        // Aggiornamento indice [4] dell'array per inserimento ID regione agraria corretto
        ${'terreno' . $count}[4] = ${'regioneAgrariaTerreno' . $count};
    }

    // Inserimento composizione ordine
    for($count = 1; $count < $numero_terreni + 1; $count++) {
        $inserimento_composizione_ordini__query = "INSERT INTO tabella_ordini__composizione(fKey_OrdineRif, desc_NumProgTerOrd, fKey_ProvinciaRif, fKey_ComuneRif, fKey_RegAgrRif, fKey_ColturaRif, desc_SupTerrenoHa) VALUES(" . ${'terreno' . $count}[0] . ", " . ${'terreno' . $count}[1] . ", " . ${'terreno' . $count}[2] . ", " . ${'terreno' . $count}[3] . ", " . ${'terreno' . $count}[4] . ", " . ${'terreno' . $count}[5] . ", " . ${'terreno' . $count}[6] . ")";
        $inserimento_composizione_ordini__execute = $istanzaConnessioneDatabase->query($inserimento_composizione_ordini__query);
    }
?>

<div class="sub-container-100">
    <h1>Ci sei quasi. Procedi con il pagamento.</h1>
    <p>
    Il prezzo per questo ordine è di euro <?php echo numeroFormattato($prezzo_ordine); ?> comprensivo di imposte e di rivalse contributive.<br>
        Clicca qui sotto per avviare la procedura di pagamento. Al termine, oltre a ricevere via e-mail la ricevuta di avvenuto pagamento, verrai reindirizzato ad una pagina contenente un riepilogo dei dati inseriti, nonchè i valori economici calcolati (ti verranno anche recapitati via e-mail nel caso tu abbia spuntato la casella nella pagina precedente).
    </p>

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="ragioneria@mozzino.it">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="item_name" value="Saldo ordine numero <?php echo $id_ordine; ?>">
        <input type="hidden" name="custom" value="<?php echo $token_ordine; ?>">
        <input type="hidden" name="amount" value="<?php echo $prezzo_ordine; ?>">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="return" value="https://www.stimaterreni.it/index.php?page=post_pagamento">
        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="cancel_return" value="https://www.stimaterreni.it/index.php?page=annulla_ordine&token_ordine=<?php echo $token_ordine; ?>">
        <input type="submit" name="submit" value="Acquista" style="padding: 5px 10px;">
    </form>

</div>