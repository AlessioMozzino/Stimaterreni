<?php ob_start(); ?>
<?php
    /* Inclusione INIT */
    require_once(__DIR__ . '/include/init.php');

    /* Variabile booleana per il controllo di esistenza del parametro pag */
    $pagExist = isset($_GET['page']);
    /* Se non esiste la si definisce e vi si assegna "home" come contenuto */
    if(!$pagExist) { $pag = "home"; }
    /* Altrimenti si legge il contenuto da URL */
    else { $pag = $_GET['page']; }
?>

<!DOCTYPE html>
<html>
    <head>

        <!-- Metadati -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Stima il valore economico di un terreno agricolo in modo automatico">
        <meta name="keywords" content="terreno agricolo, terreno, stima, valutazione, stima terreno agricolo, terreno agricolo vendita">

        <!-- Fogli di stile personale -->
        <link rel="stylesheet" href="css/style.css">

        <!-- Font google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300&family=Roboto:wght@300&display=swap" rel="stylesheet">

        <!-- Varie -->
        <title>
            <?php
                if($pag == 'home') { echo 'stimaterreni.it - Stima automatica di un terreno agricolo'; }
                elseif($pag == 'chi_siamo') { echo 'stimaterreni.it - Diamo la possibilità a chiunque di conoscere il valore economico di un terreno agricolo'; }
                elseif($pag == 'funzionamento') { echo 'stimaterreni.it - Abbiamo reso tutto molto semplice'; }
                elseif($pag == 'contatti') { echo 'stimaterreni.it - Per qualsiasi dubbio o informazione non esitare a contattarci'; }
                elseif($pag == 'acquista_servizio') { echo 'stimaterreni.it - Quanti terreni vuoi valutare?'; }
                elseif($pag == 'nuovo_ordine') { echo 'stimaterreni.it - Compila tutti i campi necessari alla valutazione'; }
                elseif($pag == 'cassa') { echo 'stimaterreni.it - Il tuo ordine è stato salvato. Procedi con il pagamento.'; }
                elseif($pag == 'post_pagamento') { echo 'stimaterreni.it - Gestione transazione in corso...'; }
                elseif($pag == 'evasione_ordine') { echo 'stimaterreni.it - Il tuo ordine è stato evaso'; }
                elseif($pag == 'annulla_ordine') { echo 'stimaterreni.it - Ci dispiace che il servizio non abbia soddisfatto le tue aspettative'; }
                elseif($pag == 'pagamenti') { echo 'stimaterreni.it - Ci affidiamo a PayPal per la gestione dei pagamenti'; }
                elseif($pag == 'privacy') { echo 'stimaterreni.it - Politica sulla privacy'; }
                elseif($pag == 'cookie') { echo 'stimaterreni.it - Politica sui cookie'; }
                elseif($pag == 'condizioni_vendita') { echo 'stimaterreni.it - Condizioni di vendita'; }
            ?>
        </title>

        <!-- Script -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.10.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-6EQNHN9QDC"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-6EQNHN9QDC');
        </script>

        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
        <link rel="manifest" href="img/site.webmanifest">
    
    </head>

    <body>

        <div class="container">
            <!-- Inizio header -->
            <header class="header">
                <?php include(__DIR__ . '/template/header.php'); ?>
            </header>
            <!-- Fine header -->

            <!-- Inizio main -->
            <main class="main">
                <!-- Inizio menu -->
                <div class="hidden" id="main-menu">
                    <nav>
                        <a href="index.php?page=home" class="menu-link">home</a>
                        <a href="index.php?page=chi_siamo" class="menu-link">chi siamo</a>
                        <a href="index.php?page=funzionamento" class="menu-link">funzionamento</a>
                        <a href="index.php?page=contatti" class="menu-link">contattaci</a>
                        <a href="index.php?page=acquista_servizio" class="menu-link">acquista il servizio</a>
                    </nav>
                </div>
                <!-- Fine menu -->

                <!-- Inizio content (dinamico da parametro pag) -->
                <div class="main-sub-container-content">
                    <?php
                        /* Inclusione dinamica in base al valore del parametro pag */
                        include(__DIR__ . '/template/page/' . $pag . '.php');
                    ?>
                </div>
                <!-- Fine content -->
            </main>
            <!-- Fine main -->

            <!-- Inizio footer -->
            <footer class="footer">
                <?php include(__DIR__ . '/template/footer.php'); ?>
            </footer>
            <!-- Fine footer -->
        </div>

        <!-- Inizio script per la visualizzazione del menu (mobile-first) -->
        <script>
            let pulsanteMenu = document.getElementById('img-header-menu-btn');
            let contenitoreMenu = document.getElementById('main-menu');

            pulsanteMenu.addEventListener('click', () => {
                if(contenitoreMenu.getAttribute('class') == 'hidden') {
                    contenitoreMenu.setAttribute('class', 'active');
                } else if(contenitoreMenu.getAttribute('class') == 'active') {
                    contenitoreMenu.setAttribute('class', 'hidden');
                }
            });
        </script>
        <!-- Fine script per la visualizzazione del menu (mobile-first) -->
    
    </body>
</html>
<?php ob_end_flush(); ?>