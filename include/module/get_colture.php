<?php
  require_once(__DIR__ . '/../db/conn.php');

  /* Recupero id zona agraria in base al comune scelto */
  $ComuneId = $_POST['pKey_ComuneId'];
  $recupero_regione_agraria__query = "SELECT fKey_RegAgrRif FROM tabella_comuni WHERE pKey_ComuneId = '$ComuneId' AND flag_ComuneStato = 1";
  $recupero_regione_agraria__execute = $istanzaConnessioneDatabase->query($recupero_regione_agraria__query);
  $recupero_regione_agraria__datum = $recupero_regione_agraria__execute->fetch_assoc();
  $RegAgrId = $recupero_regione_agraria__datum['fKey_RegAgrRif'];

  /* Recupero colture in base alla zona agraria di riferimento */
  $recupero_colture__query = "SELECT tabella_colture.pKey_ColturaId AS id_coltura, tabella_colture.desc_ColturaNome AS nome_coltura FROM tabella_colture INNER JOIN tabella_regioni_agrarie_colture ON tabella_colture.pKey_ColturaId = tabella_regioni_agrarie_colture.fKey_ColturaRif INNER JOIN tabella_regioni_agrarie ON tabella_regioni_agrarie_colture.fKey_RegAgrRif = tabella_regioni_agrarie.pKey_RegAgrId WHERE tabella_regioni_agrarie_colture.fKey_RegAgrRif = '$RegAgrId' ORDER BY tabella_colture.desc_ColturaNome";
  $recupero_colture__execute = $istanzaConnessioneDatabase->query($recupero_colture__query);
  while ($recupero_colture__datum = $recupero_colture__execute->fetch_assoc()) {
    $dropdown_stampa_colture = "<option value=".$recupero_colture__datum['id_coltura'].">".$recupero_colture__datum['nome_coltura']."</option>";
    echo $dropdown_stampa_colture;
  }
?>
