<?php

require_once ('ConfSmarty.php');
class VInserimentoAttivita
{
    /** l'oggetto smarty incaricato di visualizzare i template */
    private $smarty;

    /**
     * Funzione che inizializza e configura smarty.
     */
    public function __construct()
    {
        $this->smarty = ConfSmarty::configuration();
    }

    /**
     * Metodo che mostra il form di inserimento attività
     * @param $errore
     * @throws SmartyException
     */
    public function mostraFormAttivita($citta,$categorie,$errore){
        //comunica a smarty di mostrare la form di inserimento
        $this->smarty->assign('citta',$citta);
        $this->smarty->assign('categorie',$categorie);
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/add_activity.tpl');
    }

    /**
     * Metodo per recuperare i dati dell'attività da inserire
     * e verificare la correttezza delle immagini in termini di dimensioni ed estensione.
     * @return $annuncio  (array associativo). In caso di errori nell'upload file, il primo campo vale null e
     * il secondo contiene la stringa di errore, altrimenti il primo campo contiene l' array con i dati dell' attività
     * e il secondo è la stringa vuota
     */
    public function recuperaDatiAttivita(){
        $dati = array();
        //costruzione dell'array con i dati dell' attività
        if(isset($_POST['nome'])){
            $dati['nome'] = $_POST['nome'];
        }
        if(isset($_POST['descrizione'])){
            $dati['descrizione'] = $_POST['descrizione'];
        }
        if(isset($_POST['indirizzo'])){
            $dati['indirizzo'] = $_POST['indirizzo'];
        }
        if(isset($_POST['categorie'])){
            $dati['categorie'] = $_POST['categorie'];
        }
        if(isset($_POST['citta'])){
            $dati['citta'] = $_POST['citta'];
        }
        if(isset($_FILES['gallery'])) {
            $tempnames = $_FILES['gallery']['tmp_name']; //array di tempname img
            $types = $_FILES['gallery']['type']; //array di types
            $size = $_FILES['gallery']['size'];
            $max_size = 5242880; //dimensione massima in byte consentita
            $gallery = array();
            $error="";
                for ($i = 0; $i < count($tempnames); $i++) {
                    if (is_uploaded_file($tempnames[$i]) && static::check_ext($types[$i]) && $size[$i] <= $max_size) {
                        $fotog = file_get_contents($tempnames[$i]);  // lo uso per trasformare il file in una stringa
                        $fotogalleryobj = new EImmagine($fotog, $types[$i]);
                        array_push($gallery, $fotogalleryobj); //costruzione dell'array di EImmagine gallery
                    } else {
                        $gallery=null;
                        $error = $error . static::get_error($tempnames[$i], $types[$i], $size[$i], $max_size)."immagine ".($i+1);
                    }
                }
                $dati['gallery'] = $gallery;
                $errore=$error;
        }
        date_default_timezone_set('CET');
        $data = date ("Y-m-d H:m:s");
        $arr = explode(" ", $data);
        $dati['data'] = $arr[0];
        $dati['ora'] = $arr[1];
        $annuncio=array($dati,$errore);
        return $annuncio;
    }

    /**
     * Questo metodo verifica che il file di cui si vuole fare l' upload sia effettivamente un' immagine
     * @param $types  content-type del file
     * @return bool
     */

    static function check_ext($types) {

        switch($types) {
            case "image/png":
                return true;
                break;
            case "image/jpg":
                return true;
                break;
            case "image/jpeg":
                return true;
                break;
            case "image/gif":
                return true;
                break;
            default:
                return false;
                break;
        }

    }

    /**
     * Metodo che specifica il tipo di errore
     * @param $tempnames
     * @param $types
     * @param $size
     * @param $max_size
     * @return string
     */

    static function get_error($tempnames, $types, $size, $max_size) {
        $errore=" ";
        if(!is_uploaded_file($tempnames)) {
            $errore = $errore . "File caricato in modo non corretto.\n";
        }

        else if(!static::check_ext($types)) {
            $errore=$errore."Estensione del file non ammessa.\n";
        }
        else if($size > $max_size) {
            $errore=$errore."Dimensione dell' immagine troppo grande.\n";
        }
        return $errore;
    }


}