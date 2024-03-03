<style>
    input, textarea {
        -ms-box-sizing: content-box;
        -moz-box-sizing: content-box;
        -webkit-box-sizing: content-box; 
        box-sizing: content-box;
        padding: 5px 10px;
        margin: 5px auto;
    }
    
    #messaggio_contatto {
        resize: none;
    }

    .errore_compilazione {
        border: 2px solid red;
    }

    #messaggio_inviato {
        background-color: #D5F5E3;
        color: #196F3D;
        padding: 5px;
    }

    #messaggio_non_inviato {
        background-color: #FADBD8;
        color: #C0392B;
        padding: 5px;
    }
</style>

<div class="sub-container-100">
    <h1>Per qualsiasi dubbio o informazione non esitare a contattarci.</h1>
    <p>
        Compila il form qui in basso per richiedere informazioni aggiuntive, darci consigli o segnalarci qualche malfunzionamento.
    </p>
    <form action="index.php?page=contatti" method="post">
        <label for="nome_contatto">Come ti chiami? *</label><br>
        <input type="text" name="nome_contatto" id="nome_contatto" autofocus><br>
        <label for="email_contatto">Qual'è il tuo indirizzo e-mail? *</label><br>
        <input type="email" name="email_contatto" id="email_contatto"><br>
        <label for="messaggio_contatto">Scrivi qui il tuo messaggio *</label><br>
        <textarea name="messaggio_contatto" id="messaggio_contatto" cols="30" rows="10"></textarea><br>
        <input type="submit" name="invia_contatto" id="invia_contatto" value="Invia messaggio">
        
<?php
            if(isset($_POST['invia_contatto'])) {
                /* Recupero valori campi per invio e-mail */
                $nome_mittente = $_POST['nome_contatto'];
                $email_mittente = $_POST['email_contatto'];
                $messaggio_mittente = $_POST['messaggio_contatto'];

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: Stimaterreni.it <' . $email_mittente . '>' . "\r\n";

                $email_destinatario = 'alessiomozzino@gmail.com';
                $oggetto_email = 'Stimaterreni.it - Richiesta di informazioni da ' . $nome_mittente . ' (' . $email_mittente . ')';

                if(!mail($email_destinatario, $oggetto_email, $messaggio_mittente, $headers)) {
                    header('location:https://www.stimaterreni.it/index.php?page=contatti_ko');
                } else {
                    header('location:https://www.stimaterreni.it/index.php?page=contatti_ok');
                }

            /* Dinamicità messaggio */
            $messaggio_inviato = isset($_GET['invio']);
            echo 'prova' . $messaggio_inviato;
            if(!$messaggio_inviato) { return; }
            else{
                $esito_invio = $_GET['invio'];
                if($esito_invio == 'ok') {
            
?>
        <p id="messaggio_inviato">Messaggio inviato correttamente. Grazie per averci contattato</p>
        <?php
                } elseif($esito_invio == 'ko') {
        ?>
        
        <p id="messaggio_non_inviato">Errore durante l'invio del messaggio. Riprova.</p>

        <?php } } } ?>

    </form>

    <p>* campo obbligatorio</p>

    <?php
        /*  */
    ?>

    <script>
        let nomeContatto = document.getElementById('nome_contatto');
        let emailContatto = document.getElementById('email_contatto');
        let messaggioContatto = document.getElementById('messaggio_contatto');
        let pulsanteInvio = document.getElementById('invia_contatto');

        /* Classe di errore */
        errore_compilazione = 'errore_compilazione';

        pulsanteInvio.addEventListener('click', (e) => {
            /* Campo nome vuoto */
            if(!nomeContatto.value) {
                nomeContatto.setAttribute('class', errore_compilazione);
                e.preventDefault();
            } else {
                nomeContatto.removeAttribute('class');
            }
            /* Campo e-mail vuoto */
            if(!emailContatto.value) {
                emailContatto.setAttribute('class', errore_compilazione);
                e.preventDefault();
            } else {
                emailContatto.removeAttribute('class');
            }
            /* Campo messaggio vuoto */
            if(!messaggioContatto.value) {
                messaggioContatto.setAttribute('class', errore_compilazione);
                e.preventDefault();
            } else {
                messaggioContatto.removeAttribute('class');
            }
        });
    </script>

</div>