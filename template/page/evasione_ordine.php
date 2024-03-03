<?php
    /* Verifica esistenza token nell'url */
    $esistenza_token_url = isset($_GET['token_ordine']);
    if($esistenza_token_url) {
        $token_ordine = $_GET['token_ordine'];

        /* Verifica esistenza token */
        $esistenza_token_db__query = "SELECT COUNT(desc_OrdineToken) FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
        $esistenza_token_db__execute = $istanzaConnessioneDatabase->query($esistenza_token_db__query);
        $esistenza_token_db__datum = $esistenza_token_db__execute->fetch_assoc();
        $count_token_db = $esistenza_token_db__datum['COUNT(desc_OrdineToken)'];

        if($count_token_db > 0) {
            /* Procedura di recupero info ordine da token */
            $recupero_info_ordine_da_token_url__query = "SELECT * FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
            $recupero_info_ordine_da_token_url__execute = $istanzaConnessioneDatabase->query($recupero_info_ordine_da_token_url__query);
            $recupero_info_ordine_da_token_url__datum = $recupero_info_ordine_da_token_url__execute->fetch_assoc();
            $id_ordine_da_token_url = $recupero_info_ordine_da_token_url__datum['pKey_OrdineId'];
            $flag_invio_email = $recupero_info_ordine_da_token_url__datum['flag_OrdineEmail'];
            $id_cliente = $recupero_info_ordine_da_token_url__datum['fKey_ClienteRif'];

            /* Controllo esistenza ID ordine nella tabella delle transazioni */
            $esistenza_id_ordine_tabella_transazioni__query = "SELECT COUNT(fKey_OrdineRif) FROM tabella_transazioni WHERE fKey_OrdineRif = '$id_ordine_da_token_url'";
            $esistenza_id_ordine_tabella_transazioni__execute = $istanzaConnessioneDatabase->query($esistenza_id_ordine_tabella_transazioni__query);
            $esistenza_id_ordine_tabella_transazioni__datum = $esistenza_id_ordine_tabella_transazioni__execute->fetch_assoc();
            $esistenza_id_ordine_tabella_transazioni = $esistenza_id_ordine_tabella_transazioni__datum['COUNT(fKey_OrdineRif)'];

            if($esistenza_id_ordine_tabella_transazioni > 0) {
                /* Cambio stato ordine da "in lavorazione" a "evaso" -> da fatturare */
                $aggiornamento_stato_ordine__query = "UPDATE tabella_ordini SET fKey_OrdineStato = 'OK' WHERE pKey_OrdineId = '$id_ordine_da_token_url'";
                $aggiornamento_stato_ordine__execute = $istanzaConnessioneDatabase->query($aggiornamento_stato_ordine__query);
?>
    <style>
    fieldset {
        border: 1px solid lightgrey; 
        margin-bottom: 15px;
    }
</style>

<div class="sub-container-100">
    <h1>Il tuo ordine è stato evaso.</h1>
    <p>
        Di seguito troverai un riepilogo dei dati inseriti, nonchè i valori economici calcolati. Speriamo di aver soddisfatto le tue aspettative. Faccelo sapere direttamente dalla sezione "contattaci".<br><br>
        <?php
            /* Recupero info cliente da ID */
            $recupero_info_cliente__query = "SELECT * FROM tabella_clienti WHERE pKey_ClienteId = '$id_cliente'";
            $recupero_info_cliente__execute = $istanzaConnessioneDatabase->query($recupero_info_cliente__query);
            $recupero_info_cliente__datum = $recupero_info_cliente__execute->fetch_assoc();
            $email_cliente = $recupero_info_cliente__datum['desc_ClienteEmail'];
            $nome_cliente = $recupero_info_cliente__datum['desc_ClienteNome'];

            /* Ciclo while per recupero informazioni su composizione ordine */
            $recupero_composizione_ordine__query = "SELECT * FROM tabella_ordini__composizione WHERE fKey_OrdineRif = '$id_ordine_da_token_url'";
            $recupero_composizione_ordine__execute = $istanzaConnessioneDatabase->query($recupero_composizione_ordine__query);
            while($recupero_composizione_ordine__datum = $recupero_composizione_ordine__execute->fetch_assoc()) {
                $numero_terreno_per_ordine = $recupero_composizione_ordine__datum['desc_NumProgTerOrd'];
                $id_provincia_terreno = $recupero_composizione_ordine__datum['fKey_ProvinciaRif'];
                $id_comune_terreno = $recupero_composizione_ordine__datum['fKey_ComuneRif'];
                $id_regione_agraria_terreno = $recupero_composizione_ordine__datum['fKey_RegAgrRif'];
                $id_coltura_terreno = $recupero_composizione_ordine__datum['fKey_ColturaRif'];
                $superficie_terreno = $recupero_composizione_ordine__datum['desc_SupTerrenoHa'];

                /* Recupero nome provincia */
                $recupero_nome_provincia__query = "SELECT desc_ProvinciaNome FROM tabella_province WHERE pKey_ProvinciaId = '$id_provincia_terreno'";
                $recupero_nome_provincia__execute = $istanzaConnessioneDatabase->query($recupero_nome_provincia__query);
                $recupero_nome_provincia__datum = $recupero_nome_provincia__execute->fetch_assoc();
                $nome_provincia_terreno = $recupero_nome_provincia__datum['desc_ProvinciaNome'];

                /* Recupero nome comune */
                $recupero_nome_comune__query = "SELECT desc_ComuneNome FROM tabella_comuni WHERE pKey_ComuneId = '$id_comune_terreno'";
                $recupero_nome_comune__execute = $istanzaConnessioneDatabase->query($recupero_nome_comune__query);
                $recupero_nome_comune__datum = $recupero_nome_comune__execute->fetch_assoc();
                $nome_comune_terreno = $recupero_nome_comune__datum['desc_ComuneNome'];

                /* Recupero nome regione agraria e numero per provincia */
                $recupero_nome_numero_regione_agraria__query = "SELECT desc_RegAgrNome, desc_RegAgrNumPro FROM tabella_regioni_agrarie WHERE pKey_RegAgrId = '$id_regione_agraria_terreno'";
                $recupero_nome_numero_regione_agraria__execute = $istanzaConnessioneDatabase->query($recupero_nome_numero_regione_agraria__query);
                $recupero_nome_numero_regione_agraria__datum = $recupero_nome_numero_regione_agraria__execute->fetch_assoc();
                $nome_regione_agraria_terreno = $recupero_nome_numero_regione_agraria__datum['desc_RegAgrNome'];
                $numero_regione_agraria_terreno_per_provincia = $recupero_nome_numero_regione_agraria__datum['desc_RegAgrNumPro'];

                /* Recupero nome coltura */
                $recupero_nome_coltura__query = "SELECT desc_ColturaNome FROM tabella_colture WHERE pKey_ColturaId = '$id_coltura_terreno'";
                $recupero_nome_coltura__execute = $istanzaConnessioneDatabase->query($recupero_nome_coltura__query);
                $recupero_nome_coltura__datum = $recupero_nome_coltura__execute->fetch_assoc();
                $nome_coltura_terreno = $recupero_nome_coltura__datum['desc_ColturaNome'];

                /* Recupero prezzo unitario e anno di riferimento dalla tabella associativa (reg_agr/coltura) */
                $recupero_prezzo_anno__query = "SELECT desc_PrezzoUnitario, desc_AnnoPrezzo FROM tabella_regioni_agrarie_colture WHERE fKey_RegAgrRif = '$id_regione_agraria_terreno' AND fKey_ColturaRif = '$id_coltura_terreno' AND flag_PrezzoStato = 1";
                $recupero_prezzo_anno__execute = $istanzaConnessioneDatabase->query($recupero_prezzo_anno__query);
                $recupero_prezzo_anno__datum = $recupero_prezzo_anno__execute->fetch_assoc();
                $prezzo_unitario = $recupero_prezzo_anno__datum['desc_PrezzoUnitario'];
                $anno_prezzo = $recupero_prezzo_anno__datum['desc_AnnoPrezzo'];

                /* Calcolo valore economico!! */
                $valore_economico_terreno = $superficie_terreno * $prezzo_unitario;
        ?>
        <fieldset>
            <legend>Terreno numero <b><?php echo $numero_terreno_per_ordine; ?></b></legend>
            Provincia: <b><?php echo $nome_provincia_terreno; ?></b><br>
            Comune: <b><?php echo $nome_comune_terreno; ?></b><br>
            Nome regione agraria: <b><?php echo $nome_regione_agraria_terreno; ?></b><br>
            Numero regione agraria: <b><?php echo $numero_regione_agraria_terreno_per_provincia; ?></b><br>
            Superficie (ettari): <b><?php echo numeroFormattato($superficie_terreno); ?></b><br>
            Coltura: <b><?php echo $nome_coltura_terreno; ?></b><br>
            Prezzo unitario (euro/ettaro): <b><?php echo numeroFormattato($prezzo_unitario); ?></b><br>
            Anno di riferimento prezzo: <b><?php echo $anno_prezzo; ?></b><br><br>
            Valore economico (euro): <b><?php echo numeroFormattato($valore_economico_terreno); ?></b> *
        </fieldset>
        <?php
            }

            /* Procedura per l'invio della e-mail al cliente (spunta su casella in fase di ordinazione) */
            if($flag_invio_email == 1) {
                $messaggio_email = 'Gentile <b>' . $nome_cliente . '</b>,<br>';
                $messaggio_email .= 'di seguito riportiamo un riepilogo dei dati da te inseriti comprensivi dei valori economici calcolati:<br><br>';

                $conteggio_numero_terreni_ordine__query = "SELECT COUNT(*) FROM tabella_ordini__composizione WHERE fKey_OrdineRif = '$id_ordine_da_token_url'";
                $conteggio_numero_terreni_ordine__execute = $istanzaConnessioneDatabase->query($conteggio_numero_terreni_ordine__query);
                $conteggio_numero_terreni_ordine__datum = $conteggio_numero_terreni_ordine__execute->fetch_assoc();
                $conteggio_numero_terreni_ordine = $conteggio_numero_terreni_ordine__datum['COUNT(*)'];

                /* Ciclo for per recupero valori */
                for($count = 1; $count < $conteggio_numero_terreni_ordine + 1; $count++) {
                    $recupero_composizione_ordine__query = "SELECT * FROM tabella_ordini__composizione WHERE fKey_OrdineRif = '$id_ordine_da_token_url' AND desc_NumProgTerOrd = '$count'";
                    $recupero_composizione_ordine__execute = $istanzaConnessioneDatabase->query($recupero_composizione_ordine__query);
                    $recupero_composizione_ordine__datum = $recupero_composizione_ordine__execute->fetch_assoc();

                    ${'numero_terreno' . $count} = $recupero_composizione_ordine__datum['desc_NumProgTerOrd'];
                    ${'id_provincia_terreno' . $count} = $recupero_composizione_ordine__datum['fKey_ProvinciaRif'];
                    ${'id_comune_terreno' . $count} = $recupero_composizione_ordine__datum['fKey_ComuneRif'];
                    ${'id_reg_agr_terreno' . $count} = $recupero_composizione_ordine__datum['fKey_RegAgrRif'];
                    ${'id_coltura_terreno' . $count} = $recupero_composizione_ordine__datum['fKey_ColturaRif'];
                    ${'superficie_ha_terreno' . $count} = $recupero_composizione_ordine__datum['desc_SupTerrenoHa'];                
                
                    /* Recupero nome provincia */
                    $recupero_nome_provincia__query = "SELECT desc_ProvinciaNome FROM tabella_province WHERE pKey_ProvinciaId = " . ${'id_provincia_terreno' . $count};
                    $recupero_nome_provincia__execute = $istanzaConnessioneDatabase->query($recupero_nome_provincia__query);
                    $recupero_nome_provincia__datum = $recupero_nome_provincia__execute->fetch_assoc();
                    ${'nome_provincia_terreno' . $count} = $recupero_nome_provincia__datum['desc_ProvinciaNome'];
                
                    /* Recupero nome comune */
                    $recupero_nome_comune__query = "SELECT desc_ComuneNome FROM tabella_comuni WHERE pKey_ComuneId = " . ${'id_comune_terreno' . $count};
                    $recupero_nome_comune__execute = $istanzaConnessioneDatabase->query($recupero_nome_comune__query);
                    $recupero_nome_comune__datum = $recupero_nome_comune__execute->fetch_assoc();
                    ${'nome_comune_terreno' . $count} = $recupero_nome_comune__datum['desc_ComuneNome'];

                    /* Recupero nome regione agraria e numero per provincia */
                    $recupero_nome_numero_regione_agraria__query = "SELECT desc_RegAgrNome, desc_RegAgrNumPro FROM tabella_regioni_agrarie WHERE pKey_RegAgrId = " . ${'id_reg_agr_terreno' . $count};
                    $recupero_nome_numero_regione_agraria__execute = $istanzaConnessioneDatabase->query($recupero_nome_numero_regione_agraria__query);
                    $recupero_nome_numero_regione_agraria__datum = $recupero_nome_numero_regione_agraria__execute->fetch_assoc();
                    ${'nome_reg_agr_terreno' . $count} = $recupero_nome_numero_regione_agraria__datum['desc_RegAgrNome'];
                    ${'numero_reg_agr_terreno_per_provincia' . $count} = $recupero_nome_numero_regione_agraria__datum['desc_RegAgrNumPro'];

                    /* Recupero nome coltura */
                    $recupero_nome_coltura__query = "SELECT desc_ColturaNome FROM tabella_colture WHERE pKey_ColturaId = " . ${'id_coltura_terreno' . $count};
                    $recupero_nome_coltura__execute = $istanzaConnessioneDatabase->query($recupero_nome_coltura__query);
                    $recupero_nome_coltura__datum = $recupero_nome_coltura__execute->fetch_assoc();
                    ${'nome_coltura_terreno' . $count} = $recupero_nome_coltura__datum['desc_ColturaNome'];

                    /* Recupero prezzo unitario e anno di riferimento dalla tabella associativa (reg_agr/coltura) */
                    $recupero_prezzo_anno__query = "SELECT desc_PrezzoUnitario, desc_AnnoPrezzo FROM tabella_regioni_agrarie_colture WHERE fKey_RegAgrRif = " . ${'id_reg_agr_terreno' . $count} . " AND fKey_ColturaRif = " . ${'id_coltura_terreno' . $count} . " AND flag_PrezzoStato = 1";
                    $recupero_prezzo_anno__execute = $istanzaConnessioneDatabase->query($recupero_prezzo_anno__query);
                    $recupero_prezzo_anno__datum = $recupero_prezzo_anno__execute->fetch_assoc();
                    ${'prezzo_unitario_terreno' . $count} = $recupero_prezzo_anno__datum['desc_PrezzoUnitario'];
                    ${'anno_prezzo_unitario_terreno' . $count} = $recupero_prezzo_anno__datum['desc_AnnoPrezzo'];
                
                    /* Calcolo valore economico!! */
                    ${'valore_economico_terreno' . $count} = ${'superficie_ha_terreno' . $count} * ${'prezzo_unitario_terreno' . $count};

                    /* Aggiornamento messaggio e-mail */
                    $messaggio_email .= "Terreno numero: <b>" . ${'numero_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Provincia: <b>" . ${'nome_provincia_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Comune: <b>" . ${'nome_comune_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Nome regione agraria: <b>" . ${'nome_reg_agr_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Numero regione agraria: <b>" . ${'numero_reg_agr_terreno_per_provincia' . $count} . "</b><br>";
                    $messaggio_email .= "Superficie (ettari): <b>" . numeroFormattato(${'superficie_ha_terreno' . $count}) . "</b><br>";
                    $messaggio_email .= "Coltura: <b>" . ${'nome_coltura_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Prezzo unitario (euro/ettaro): <b>" . numeroFormattato(${'prezzo_unitario_terreno' . $count}) . "</b><br>";
                    $messaggio_email .= "Anno di riferimento prezzo: <b>" . ${'anno_prezzo_unitario_terreno' . $count} . "</b><br>";
                    $messaggio_email .= "Valore economico (euro): <b>" . numeroFormattato(${'valore_economico_terreno' . $count}) . "</b><br><br>";
                }

                $messaggio_email .= 'Grazie per aver scelto il nostro servizio<br>Il team di stimaterreni.it';

                /* Preparazione per invio evasione ordine via e.mail al cliente */
                $email_mittente = 'info@stimaterreni.it';

                $email_destinatario = $email_cliente;

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: Stimaterreni.it <' . $email_mittente . '>' . "\r\n";

                $oggetto_email = 'Evasione ordine numero ' . $id_ordine_da_token_url;

                /* Invio email */
                mail($email_destinatario, $oggetto_email, $messaggio_email, $headers);
            }

        ?>
        * <i>Il valore calcolato non è il valore reale: esso, infatti, non tiene conto di molti altri fattori, quali ad esempio lo stato di manutenzione, la presenza di ipoteche, l'orientamento. Il valore calcolato deve quindi essere considerato come puramente indicativo.</i>
    </p>
</div>
<?php
            } else {
?>
        <div class="sub-container-100">
        <h1>Attenzione.</h1>
        <p>
            Sembra che questo ordine non sia stato pagato. Per poter proseguire clicca qui sotto per riprendere la procedura di pagamento. Grazie.
        </p>

        <?php
            /* Recupero prezzo ordine da token */
            $recupero_prezzo_ordine_da_token__query = "SELECT desc_OrdinePrezzo FROM tabella_ordini WHERE desc_OrdineToken = '$token_ordine'";
            $recupero_prezzo_ordine_da_token__execute = $istanzaConnessioneDatabase->query($recupero_prezzo_ordine_da_token__query);
            $recupero_prezzo_ordine_da_token__datum = $recupero_prezzo_ordine_da_token__execute->fetch_assoc();
            $prezzo_ordine = $recupero_prezzo_ordine_da_token__datum['desc_OrdinePrezzo'];
        ?>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="business" value="ragioneria@mozzino.it">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="item_name" value="Saldo ordine numero <?php echo $id_ordine_da_token_url; ?>">
            <input type="hidden" name="custom" value="<?php echo $token_ordine; ?>">
            <input type="hidden" name="amount" value="<?php echo $prezzo_ordine; ?>">
            <input type="hidden" name="currency_code" value="EUR">
            <input type="hidden" name="return" value="https://www.stimaterreni.it/index.php?page=post_pagamento">
            <input type="hidden" name="rm" value="2">
            <input type="hidden" name="cancel_return" value="https://www.stimaterreni.it/index.php?page=annulla_ordine&token_ordine=<?php echo $token_ordine; ?>">
            <input type="submit" name="submit" value="Acquista" style="padding: 5px 10px;">
        </form>

    </div>
<?php
            }
        } else {
?>
    <div class="sub-container-100">
        <h1>Attenzione.</h1>
        <p>
            Sono stati rilevati errori di corrispondenza tra il token e l'id ordine. Per favore, riprova.
        </p>
    </div>
<?php
            
        }
    }
?>