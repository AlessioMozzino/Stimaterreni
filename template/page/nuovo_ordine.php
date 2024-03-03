<?php
    $numeroTerreniDaValutare = $_GET['numero_terreni'];
    if(!($numeroTerreniDaValutare > 0) || !($numeroTerreniDaValutare <= numeroMassimoStimePerSingoloOrdine)) {
?>
<!-- Inizio contenitore per errore numero di terreni -->
<div class="sub-container-100">
    <h1>Sembra che il numero di terreni da valutare non sia valido. Riprova.</h1>
</div>
<!-- Fine contenitore per errore numero di terreni -->
<?php
    } else {
?>

<style>
    fieldset {
        border: 1px solid lightgrey; 
        margin-bottom: 15px;
    }
    select, input {
        -ms-box-sizing: content-box;
        -moz-box-sizing: content-box;
        -webkit-box-sizing: content-box; 
        box-sizing: content-box;
        padding: 5px 10px;
        margin: 5px auto;
    }
    .errore_compilazione {
        border: 2px solid red;
    }
    .link_new_order_terms_privacy {
        color: black;
        font-weight: bold;
        text-decoration: none;
    }
    .link_new_order_terms_privacy:hover {
        text-decoration: underline;
    }
    .table-sezione-ordinazione {
        padding: 5px 0px;
        text-align: justify;
    }
</style>

<!-- Inizio contenitore form per dati terreni da valutare -->
<div class="sub-container-100">
    <form action="index.php?page=cassa" method="post">
        <input type="hidden" name="numero_terreni" value="<?php echo $numeroTerreniDaValutare; ?>">
        <?php
            for($count = 1; $count < $numeroTerreniDaValutare + 1; $count++) {
        ?>
        <fieldset>
            <legend>Terreno numero <b><?php echo $count; ?></b></legend>
            <label for="provinciaTerreno<?php echo $count; ?>">Inserisci la provincia</label><br>
            <select name="provinciaTerreno<?php echo $count; ?>" id="provinciaTerreno<?php echo $count; ?>" required>
                <option value="0" disabled selected>-</option>
                <?php
                    $elenco_province__query = "SELECT pKey_ProvinciaId, desc_ProvinciaNome FROM tabella_province WHERE flag_ProvinciaStato = 1 AND flag_ProvinciaStatoRAC = 1 ORDER BY desc_ProvinciaNome";
                    $elenco_province__execute = $istanzaConnessioneDatabase->query($elenco_province__query);
                    while($elenco_province__datum = $elenco_province__execute->fetch_assoc()) {
                        $pKey_ProvinciaId = $elenco_province__datum['pKey_ProvinciaId'];
                        $desc_ProvinciaNome = $elenco_province__datum['desc_ProvinciaNome'];

                        require_once(__DIR__ . '/../../include/module/get_comuni.php');
                ?>
                <option value="<?php echo $pKey_ProvinciaId; ?>"><?php echo $desc_ProvinciaNome; ?></option>
                <?php
                    }
                ?>
            </select><br>
            <label for="comuneTerreno<?php echo $count; ?>">Inserisci il comune</label><br>
            <select name="comuneTerreno<?php echo $count; ?>" id="comuneTerreno<?php echo $count; ?>" required>
                <option value="0" disabled selected>Inserisci prima la provincia</option>
                <?php require_once(__DIR__ . '/../../include/module/get_colture.php'); ?>
            </select><br>
            <label for="colturaTerreno<?php echo $count; ?>">Inserisci la coltura</label><br>
            <select name="colturaTerreno<?php echo $count; ?>" id="colturaTerreno<?php echo $count; ?>" required>
                <option value="0" disabled selected>Inserisci prima il comune</option>
            </select><br>
            <label for="superficieTerreno<?php echo $count; ?>">Inserisci la superficie in mq</label><br>
            <input type="number" name="superficieTerreno<?php echo $count; ?>" required>
        </fieldset>

        <script>
        /* Funzione recupera comuni */
        $(document).ready(function() {
            $("#provinciaTerreno<?php echo $count; ?>").change(function() {
                $("#provinciaTerreno<?php echo $count; ?> option:selected").each(function() {
                    pKey_ProvinciaId = $(this).val();
                    $.post("./include/module/get_comuni.php", { pKey_ProvinciaId: pKey_ProvinciaId }, function(data) {
                        $("#comuneTerreno<?php echo $count; ?>").html(data);
                    });
                });
            });
        });

        /* Funzione recupera colture */
        $(document).ready(function() {
            $("#comuneTerreno<?php echo $count; ?>").change(function() {
                $("#comuneTerreno<?php echo $count; ?> option:selected").each(function() {
                    pKey_ComuneId = $(this).val();
                    $.post("./include/module/get_colture.php", { pKey_ComuneId: pKey_ComuneId }, function(data) {
                        $("#colturaTerreno<?php echo $count; ?>").html(data);
                    });
                });
            });
        });
    </script>

        <?php
            }
        ?>

        <!-- Anagrafica cliente -->
        <fieldset>
            <legend>Dati fatturazione</legend>
            <label for="tipologia_cliente">Stai acquistando come?</label><br>
            <select name="tipologia_cliente" id="tipologia_cliente" required>
                <option value="0" disabled selected>-</option>
                <?php
                    $elenco_tipologie_clienti__query = "SELECT * FROM tabella_tipologie_clienti ORDER BY desc_TipClienteDesc";
                    $elenco_tipologie_clienti__execute = $istanzaConnessioneDatabase->query($elenco_tipologie_clienti__query);
                    while($elenco_tipologie_clienti__datum = $elenco_tipologie_clienti__execute->fetch_assoc()) {
                        $tipologia_cliente_id = $elenco_tipologie_clienti__datum['pKey_TipClienteId'];
                        $tipologia_cliente_desc = $elenco_tipologie_clienti__datum['desc_TipClienteDesc'];
                ?>
                <option value="<?php echo $tipologia_cliente_id; ?>"><?php echo $tipologia_cliente_desc; ?></option>
                <?php
                    }
                ?>
            </select>

            <!-- Inizio dati da richiedere per persona fisica -->
            <div id="PF" class="intestatario-fattura">
                <label for="nome_cognome_pf">Nome e cognome</label><br>
                <input type="text" name="nome_cognome_pf" id="nome_cognome_pf"><br>
                <label for="provincia_fiscale_pf">Inserisci la provincia</label><br>
                <select name="provincia_fiscale_pf" id="provincia_fiscale_pf">
                    <option value="0" disabled selected>-</option>
                    <?php
                        $elenco_province_pf__query = "SELECT pKey_ProvinciaId, desc_ProvinciaNome FROM tabella_province WHERE flag_ProvinciaStato = 1 ORDER BY desc_ProvinciaNome";
                        $elenco_province_pf__execute = $istanzaConnessioneDatabase->query($elenco_province_pf__query);
                        while($elenco_province_pf__datum = $elenco_province_pf__execute->fetch_assoc()) {
                            $provincia_id_pf = $elenco_province_pf__datum['pKey_ProvinciaId'];
                            $provincia_nome_pf = $elenco_province_pf__datum['desc_ProvinciaNome'];
                    ?>
                    <option value="<?php echo $provincia_id_pf; ?>"><?php echo $provincia_nome_pf; ?></option>
                    <?php
                        }
                    ?>
                </select><br>
                <label for="comune_fiscale_pf">Inserisci il comune</label><br>
                <select name="comune_fiscale_pf" id="comune_fiscale_pf">
                    <option value="0" disabled selected>Inserisci prima la provincia</option>
                </select><br>
                <label for="cap_fiscale_pf">Inserisci il CAP</label><br>
                <input type="text" name="cap_fiscale_pf" id="cap_fiscale_pf"><br>
                <label for="indirizzo_fiscale_pf">Inserisci l'indirizzo</label><br>
                <input type="text" name="indirizzo_fiscale_pf" id="indirizzo_fiscale_pf"><br>
                <label for="civico_indirizzo_fiscale_pf">Inserisci il numero civico</label><br>
                <input type="text" name="civico_indirizzo_fiscale_pf" id="civico_indirizzo_fiscale_pf"><br>
                <label for="codice_fiscale_pf">Inserisci il codice fiscale</label><br>
                <input type="text" name="codice_fiscale_pf" id="codice_fiscale_pf"><br>
                <label for="indirizzo_email_pf">Inserisci l'indirizzo e-mail</label><br>
                <input type="email" name="indirizzo_email_pf" id="indirizzo_email_pf"><br>
            </div>
            <!-- Fine dati da richiedere per persona fisica -->

            <!-- Inizio dati da richiedere per persona fisica con partita iva -->
            <div id="PFIVA" class="intestatario-fattura">
                <label for="nome_cognome_pfiva">Nome e cognome</label><br>
                <input type="text" name="nome_cognome_pfiva" id="nome_cognome_pfiva"><br>
                <label for="provincia_fiscale_pfiva">Inserisci la provincia</label><br>
                <select name="provincia_fiscale_pfiva" id="provincia_fiscale_pfiva">
                    <option value="0" disabled selected>-</option>
                    <?php
                        $elenco_province_pfiva__query = "SELECT pKey_ProvinciaId, desc_ProvinciaNome FROM tabella_province WHERE flag_ProvinciaStato = 1 ORDER BY desc_ProvinciaNome";
                        $elenco_province_pfiva__execute = $istanzaConnessioneDatabase->query($elenco_province_pfiva__query);
                        while($elenco_province_pfiva__datum = $elenco_province_pfiva__execute->fetch_assoc()) {
                            $provincia_id_pfiva = $elenco_province_pfiva__datum['pKey_ProvinciaId'];
                            $provincia_nome_pfiva = $elenco_province_pfiva__datum['desc_ProvinciaNome'];
                    ?>
                    <option value="<?php echo $provincia_id_pfiva; ?>"><?php echo $provincia_nome_pfiva; ?></option>
                    <?php
                        }
                    ?>
                </select><br>
                <label for="comune_fiscale_pfiva">Inserisci il comune</label><br>
                <select name="comune_fiscale_pfiva" id="comune_fiscale_pfiva">
                    <option value="0" disabled selected>Inserisci prima la provincia</option>
                </select><br>
                <label for="cap_fiscale_pfiva">Inserisci il CAP</label><br>
                <input type="text" name="cap_fiscale_pfiva" id="cap_fiscale_pfiva"><br>
                <label for="indirizzo_fiscale_pfiva">Inserisci l'indirizzo</label><br>
                <input type="text" name="indirizzo_fiscale_pfiva" id="indirizzo_fiscale_pfiva"><br>
                <label for="civico_indirizzo_fiscale_pfiva">Inserisci il numero civico</label><br>
                <input type="text" name="civico_indirizzo_fiscale_pfiva" id="civico_indirizzo_fiscale_pfiva"><br>
                <label for="codice_fiscale_pfiva">Inserisci il codice fiscale</label><br>
                <input type="text" name="codice_fiscale_pfiva" id="codice_fiscale_pfiva"><br>
                <label for="partita_iva_pfiva">Inserisci la partita IVA</label><br>
                <input type="text" name="partita_iva_pfiva" id="partita_iva_pfiva"><br>
                <label for="indirizzo_email_pfiva">Inserisci l'indirizzo e-mail</label><br>
                <input type="email" name="indirizzo_email_pfiva" id="indirizzo_email_pfiva"><br>
                <label for="indirizzo_pec_pfiva">Inserisci l'indirizzo PEC (se presente)</label><br>
                <input type="email" name="indirizzo_pec_pfiva"><br>
                <label for="codice_sdi_pfiva">Inserisci il codice SDI (se presente)</label><br>
                <input type="text" name="codice_sdi_pfiva"><br>
            </div>
            <!-- Fine dati da richiedere per persona fisica con partita iva -->

            <!-- Inizio dati da richiedere per persona giuridica -->
            <div id="PG" class="intestatario-fattura">
                <label for="denominazione_pg">Denominazione</label><br>
                <input type="text" name="denominazione_pg" id="denominazione_pg"><br>
                <label for="legale_rappresentante_pg">Legale rappresentante</label><br>
                <input type="text" name="legale_rappresentante_pg" id="legale_rappresentante_pg"><br>
                <label for="provincia_fiscale_pg">Inserisci la provincia</label><br>
                <select name="provincia_fiscale_pg" id="provincia_fiscale_pg">
                    <option value="0" disabled selected>-</option>
                    <?php
                        $elenco_province_pg__query = "SELECT pKey_ProvinciaId, desc_ProvinciaNome FROM tabella_province WHERE flag_ProvinciaStato = 1 ORDER BY desc_ProvinciaNome";
                        $elenco_province_pg__execute = $istanzaConnessioneDatabase->query($elenco_province_pg__query);
                        while($elenco_province_pg__datum = $elenco_province_pg__execute->fetch_assoc()) {
                            $provincia_id_pg = $elenco_province_pg__datum['pKey_ProvinciaId'];
                            $provincia_nome_pg = $elenco_province_pg__datum['desc_ProvinciaNome'];
                    ?>
                    <option value="<?php echo $provincia_id_pg; ?>"><?php echo $provincia_nome_pg; ?></option>
                    <?php
                        }
                    ?>
                </select><br>
                <label for="comune_fiscale_pg">Inserisci il comune</label><br>
                <select name="comune_fiscale_pg" id="comune_fiscale_pg">
                    <option value="0" disabled selected>Inserisci prima la provincia</option>
                </select><br>
                <label for="cap_fiscale_pg">Inserisci il CAP</label><br>
                <input type="text" name="cap_fiscale_pg" id="cap_fiscale_pg"><br>
                <label for="indirizzo_fiscale_pg">Inserisci l'indirizzo</label><br>
                <input type="text" name="indirizzo_fiscale_pg" id="indirizzo_fiscale_pg"><br>
                <label for="civico_indirizzo_fiscale_pg">Inserisci il numero civico</label><br>
                <input type="text" name="civico_indirizzo_fiscale_pg" id="civico_indirizzo_fiscale_pg"><br>
                <label for="partita_iva_pg">Inserisci la partita IVA</label><br>
                <input type="text" name="partita_iva_pg" id="partita_iva_pg"><br>
                <label for="indirizzo_email_pg">Inserisci l'indirizzo e-mail</label><br>
                <input type="email" name="indirizzo_email_pg" id="indirizzo_email_pg"><br>
                <label for="indirizzo_pec_pg">Inserisci l'indirizzo PEC</label><br>
                <input type="email" name="indirizzo_pec_pg" id="indirizzo_pec_pg"><br>
                <label for="codice_sdi_pg">Inserisci il codice SDI</label><br>
                <input type="text" name="codice_sdi_pg" id="codice_sdi_pg"><br>
            </div>
            <!-- Fine dati da richiedere per persona giuridica -->

            <script>
                /* Funzione mostra/nascondi tipologia intestatario fattura */
                $(function() {
                    $('.intestatario-fattura').hide();
                    $('#tipologia_cliente').change(function(){
                        $('.intestatario-fattura').hide();
                        $('#' + $(this).val()).show();
                    });
                });

                /* Funzione recupera comune fiscale PF */
                $(document).ready(function() {
                    $("#provincia_fiscale_pf").change(function() {
                        $("#provincia_fiscale_pf option:selected").each(function() {
                            pKey_ProvinciaId = $(this).val();
                            $.post("./include/module/get_comuni.php", { pKey_ProvinciaId: pKey_ProvinciaId }, function(data) {
                                $("#comune_fiscale_pf").html(data);
                            });
                        });
                    });
                });

                /* Funzione recupera comune fiscale PFIVA */
                $(document).ready(function() {
                    $("#provincia_fiscale_pfiva").change(function() {
                        $("#provincia_fiscale_pfiva option:selected").each(function() {
                            pKey_ProvinciaId = $(this).val();
                            $.post("./include/module/get_comuni.php", { pKey_ProvinciaId: pKey_ProvinciaId }, function(data) {
                                $("#comune_fiscale_pfiva").html(data);
                            });
                        });
                    });
                });

                /* Funzione recupera comune fiscale PG */
                $(document).ready(function() {
                    $("#provincia_fiscale_pg").change(function() {
                        $("#provincia_fiscale_pg option:selected").each(function() {
                            pKey_ProvinciaId = $(this).val();
                            $.post("./include/module/get_comuni.php", { pKey_ProvinciaId: pKey_ProvinciaId }, function(data) {
                                $("#comune_fiscale_pg").html(data);
                            });
                        });
                    });
                });
            </script>

        </fieldset>

        <fieldset>
            <legend>Ordinazione</legend>
            <table>
                <tbody>
                    <tr>
                        <td valign="top" class="table-sezione-ordinazione">
                            <input type="checkbox" name="flag_valutazione_email" id="flag_valutazione_email">
                        </td>
                        <td valign="top" class="table-sezione-ordinazione">
                            <label for="flag_valutazione_email">Voglio ricevere le valutazioni anche via e-mail</label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="table-sezione-ordinazione">
                            <input type="checkbox" name="flag_privacy" id="flag_privacy" required>
                        </td>
                        <td valign="top" class="table-sezione-ordinazione">
                            <label for="flag_privacy">Ho letto ed accetto la politica sulla <a class="link_new_order_terms_privacy" href="index.php?page=privacy" target="_blank">privacy</a></label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="table-sezione-ordinazione">
                            <input type="checkbox" name="flag_termini_condizioni" id="flag_termini_condizioni" required>
                        </td>
                        <td valign="top" class="table-sezione-ordinazione">
                            <label for="flag_termini_condizioni">Ho letto ed accetto i termini e le <a class="link_new_order_terms_privacy" href="index.php?page=condizioni_vendita" target="_blank">condizioni di vendita</a></label>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="table-sezione-ordinazione">
                            <input type="checkbox" name="flag_valore_economico_indicativo" id="flag_valore_economico_indicativo" required>
                        </td>
                        <td valign="top" class="table-sezione-ordinazione">
                            <label for="flag_valore_economico_indicativo">Sono consapevole che il valore economico che verrà calcolato non è il valore economico reale ma un valore puramente indicativo</label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input id="acquista" type="submit" value="Vai alla cassa" style="padding: 5px 10px;">
        </fieldset>

        <script>
            /* Funziona convalida campi */
            let tipologiaCliente = document.getElementById('tipologia_cliente');
            let pulsanteAcquista = document.getElementById('acquista');

            /* Classe errore */
            let errore_compilazione = 'errore_compilazione';

            pulsanteAcquista.addEventListener('click', (e) => {
                if(tipologiaCliente.value == '0') {
                    tipologiaCliente.setAttribute('class', errore_compilazione);
                    e.preventDefault();
                } else {
                    tipologiaCliente.removeAttribute('class');
                }
                

                if(tipologiaCliente.value == 'PF') {
                    /* Recupero valori campi PF */
                    let nomeCognomePf = document.getElementById('nome_cognome_pf');
                    let provinciaFiscalePf = document.getElementById('provincia_fiscale_pf');
                    let comuneFiscalePf = document.getElementById('comune_fiscale_pf');
                    let capFiscalePf = document.getElementById('cap_fiscale_pf');
                    let indirizzoFiscalePf = document.getElementById('indirizzo_fiscale_pf');
                    let civicoIndirizzoFiscalePf = document.getElementById('civico_indirizzo_fiscale_pf');
                    let codiceFiscalePf = document.getElementById('codice_fiscale_pf');
                    let indirizzoEmailPf = document.getElementById('indirizzo_email_pf');

                    /* Errore se campo vuoto */
                    if(!nomeCognomePf.value) {
                        nomeCognomePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        nomeCognomePf.removeAttribute('class');
                    }

                    if(!provinciaFiscalePf.value || provinciaFiscalePf.value == '0') {
                        provinciaFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        provinciaFiscalePf.removeAttribute('class');
                    }

                    if(!comuneFiscalePf.value || comuneFiscalePf.value == '0') {
                        comuneFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        comuneFiscalePf.removeAttribute('class');
                    }

                    if(!capFiscalePf.value) {
                        capFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        capFiscalePf.removeAttribute('class');
                    }

                    if(!indirizzoFiscalePf.value) {
                        indirizzoFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoFiscalePf.removeAttribute('class');
                    }

                    if(!civicoIndirizzoFiscalePf.value) {
                        civicoIndirizzoFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        civicoIndirizzoFiscalePf.removeAttribute('class');
                    }

                    if(!codiceFiscalePf.value) {
                        codiceFiscalePf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        codiceFiscalePf.removeAttribute('class');
                    }

                    if(!indirizzoEmailPf.value) {
                        indirizzoEmailPf.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoEmailPf.removeAttribute('class');
                    }
                } else if(tipologiaCliente.value == 'PFIVA') {
                    /* Recupero valori campi PFIVA */
                    let nomeCognomePfIva = document.getElementById('nome_cognome_pfiva');
                    let provinciaFiscalePfIva = document.getElementById('provincia_fiscale_pfiva');
                    let comuneFiscalePfIva = document.getElementById('comune_fiscale_pfiva');
                    let capFiscalePfIva = document.getElementById('cap_fiscale_pfiva');
                    let indirizzoFiscalePfIva = document.getElementById('indirizzo_fiscale_pfiva');
                    let civicoIndirizzoFiscalePfIva = document.getElementById('civico_indirizzo_fiscale_pfiva');
                    let codiceFiscalePfIva = document.getElementById('codice_fiscale_pfiva');
                    let partitaIvaPfIva = document.getElementById('partita_iva_pfiva');
                    let indirizzoEmailPfIva = document.getElementById('indirizzo_email_pfiva');

                    /* Errore se campo vuoto */
                    if(!nomeCognomePfIva.value) {
                        nomeCognomePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        nomeCognomePfIva.removeAttribute('class');
                    }

                    if(!provinciaFiscalePfIva.value || provinciaFiscalePfIva.value == '0') {
                        provinciaFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        provinciaFiscalePfIva.removeAttribute('class');
                    }

                    if(!comuneFiscalePfIva.value || comuneFiscalePfIva.value == '0') {
                        comuneFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        comuneFiscalePfIva.removeAttribute('class');
                    }

                    if(!capFiscalePfIva.value) {
                        capFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        capFiscalePfIva.removeAttribute('class');
                    }

                    if(!indirizzoFiscalePfIva.value) {
                        indirizzoFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoFiscalePfIva.removeAttribute('class');
                    }

                    if(!civicoIndirizzoFiscalePfIva.value) {
                        civicoIndirizzoFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        civicoIndirizzoFiscalePfIva.removeAttribute('class');
                    }

                    if(!codiceFiscalePfIva.value) {
                        codiceFiscalePfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        codiceFiscalePfIva.removeAttribute('class');
                    }

                    if(!partitaIvaPfIva.value) {
                        partitaIvaPfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        partitaIvaPfIva.removeAttribute('class');
                    }

                    if(!indirizzoEmailPfIva.value) {
                        indirizzoEmailPfIva.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoEmailPfIva.removeAttribute('class');
                    }
                } else if(tipologiaCliente.value == 'PG') {
                    /* Recupero valori campi PG */
                    let denominazionePg = document.getElementById('denominazione_pg');
                    let legaleRappresentantePg = document.getElementById('legale_rappresentante_pg');
                    let provinciaFiscalePg = document.getElementById('provincia_fiscale_pg');
                    let comuneFiscalePg = document.getElementById('comune_fiscale_pg');
                    let capFiscalePg = document.getElementById('cap_fiscale_pg');
                    let indirizzoFiscalePg = document.getElementById('indirizzo_fiscale_pg');
                    let civicoIndirizzoFiscalePg = document.getElementById('civico_indirizzo_fiscale_pg');
                    let partitaIvaPg = document.getElementById('partita_iva_pg');
                    let indirizzoEmailPg = document.getElementById('indirizzo_email_pg');
                    let indirizzoPecPg = document.getElementById('indirizzo_pec_pg');
                    let codiceSdiPg = document.getElementById('codice_sdi_pg');

                    /* Errore se campo vuoto */
                    if(!denominazionePg.value) {
                        denominazionePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        denominazionePg.removeAttribute('class');
                    }

                    if(!legaleRappresentantePg.value) {
                        legaleRappresentantePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        legaleRappresentantePg.removeAttribute('class');
                    }

                    if(!provinciaFiscalePg.value || provinciaFiscalePg.value == '0') {
                        provinciaFiscalePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        provinciaFiscalePg.removeAttribute('class');
                    }

                    if(!comuneFiscalePg.value || comuneFiscalePg.value == '0') {
                        comuneFiscalePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        comuneFiscalePg.removeAttribute('class');
                    }

                    if(!capFiscalePg.value) {
                        capFiscalePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        capFiscalePg.removeAttribute('class');
                    }

                    if(!indirizzoFiscalePg.value) {
                        indirizzoFiscalePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoFiscalePg.removeAttribute('class');
                    }

                    if(!civicoIndirizzoFiscalePg.value) {
                        civicoIndirizzoFiscalePg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        civicoIndirizzoFiscalePg.removeAttribute('class');
                    }

                    if(!partitaIvaPg.value) {
                        partitaIvaPg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        partitaIvaPg.removeAttribute('class');
                    }

                    if(!indirizzoEmailPg.value) {
                        indirizzoEmailPg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoEmailPg.removeAttribute('class');
                    }

                    if(!indirizzoPecPg.value) {
                        indirizzoPecPg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        indirizzoPecPg.removeAttribute('class');
                    }

                    if(!codiceSdiPg.value) {
                        codiceSdiPg.setAttribute('class', errore_compilazione);
                        e.preventDefault();
                    } else {
                        codiceSdiPg.removeAttribute('class');
                    }
                }
            });
        </script>

    </form>
</div>
<!-- Fine contenitore form per dati terreni da valutare -->

<?php } ?>