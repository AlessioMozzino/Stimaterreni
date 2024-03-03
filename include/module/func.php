<?php
    /* Funzioni globali */
    
    // Inclusione file variabili globali
    require('var.php');

    // Funzione per la formattazione di un numero
    function numeroFormattato($numeroNonFormattato) {
        return number_format($numeroNonFormattato, 2, ',', '.');
    }

    // Funzione per la determinazione del numero per un calcolo percentuale (esempio: 35% > 0.35)
    function numeroCalcoloPercentuale($numeroBase) {
        return ($numeroBase / 100);
    }

    // Funzione per la generazione del token (20 caratteri alfanumerici)
    function generazioneTokenOrdine() {
        $elencoCaratteriAmmessi = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $stringaCasuale = '';
        for ($i = 0; $i < 20; $i++) {
            $indiceRecuperoLetteraNumero = rand(0, strlen($elencoCaratteriAmmessi) - 1);
            $stringaCasuale .= $elencoCaratteriAmmessi[$indiceRecuperoLetteraNumero];
        }
        return $stringaCasuale;
    }

    // Funzione per il calcolo della superficie da metri quadri a ettari (10.000 mq = 1 ha)
    function calcoloSuperficieEttariDaMetriQuadri($superficieMetriQuadri) {
        if($superficieMetriQuadri > 0) { return ($superficieMetriQuadri / 10000); }
    }

    // Funzione per il calcolo dell'importo totale ordine
    function calcoloImportoTotaleOrdine($numeroTerreniDaValutare) {
        if($numeroTerreniDaValutare > 0) { return ($numeroTerreniDaValutare * prezzoServizio); }
    }
?>