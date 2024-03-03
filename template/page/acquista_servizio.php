<style>
    .errore_compilazione {
        border: 2px solid red;
    }
</style>

<!-- Inizio contenitore form per numero di terreni da valutare -->
<div class="sub-container-100">
    <h1>Quanti terreni vuoi valutare?</h1>
    <p>Seleziona il numero di terreni per i quali vuoi richiedere la valutazione. Puoi valutare fino ad un massimo di 10 terreni per singolo ordine.</p>
    <form action="index.php" method="get">
        <input type="hidden" name="page" value="nuovo_ordine">
        <select name="numero_terreni" id="numero_terreni" style="padding: 5px 10px;">
            <option value="0" disabled selected>-</option>
            <?php
                for($count = 1; $count < numeroMassimoStimePerSingoloOrdine + 1; $count++) {
            ?>
            <option value="<?php echo $count; ?>"><?php echo $count; ?></option>
            <?php
                }
            ?>
        </select>
        <input id="pulsante_nuovo_ordine" type="submit" value="Nuovo ordine" style="padding: 5px 10px;">
    </form>
    <p><i>
        * Il prezzo del servizio è di euro 24,95 (tasse incluse) per terreno valutato<br>
        ** Prima di effettuare un ordine leggi attentamente i termini e le condizioni di vendita
    </i></p>
</div>
<!-- Fine contenitore form per numero di terreni da valutare -->

<script>
    let numeroTerreniDaValutare = document.getElementById('numero_terreni');
    let pulsanteNuovoOrdine = document.getElementById('pulsante_nuovo_ordine');

    let errore_compilazione = 'errore_compilazione';

    pulsanteNuovoOrdine.addEventListener('click', (e) => {
        if(!numeroTerreniDaValutare.value || numeroTerreniDaValutare.value == '0') {
            numeroTerreniDaValutare.setAttribute('class', errore_compilazione);
            e.preventDefault();
        } else {
            numeroTerreniDaValutare.removeAttribute('class');
        }
    });
</script>