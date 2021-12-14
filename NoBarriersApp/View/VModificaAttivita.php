<?php

require_once ('ConfSmarty.php');
class VModificaAttivita
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
    public function mostraFormAttivita($attivita){
        $this->smarty->assign('attivita',$attivita);
        $this->smarty->display('smarty/templates/modify_activity.tpl');
    }
    public function MostraFormFoto($idattivita,$idproprietario,$errore){
        $this->smarty->assign('idattivita',$idattivita);
        $this->smarty->assign('errore',$errore);
        $this->smarty->assign('idproprietario',$idproprietario);
        $this->smarty->display('smarty/templates/modifyphotoact.tpl');
    }

    /**
     * Metodo per recuperare i dati dell'attività da modificare
     */
    public function recuperaDatiAttivita()
    {
        $dati = array();
        //costruzione dell'array con i dati dell' attività
        if (isset($_POST['descrizione'])) {
            $dati['descrizione'] = $_POST['descrizione'];
        }
        if (isset($_POST['indirizzo'])) {
            $dati['indirizzo'] = $_POST['indirizzo'];
        }
        return $dati;
    }
        public function recuperaFoto(){
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
                        $error = $error . static::get_error($tempnames[$i], $types[$i], $size[$i], $max_size)."immagine ".($i+1);
                    }
                }
                $dati['gallery'] = $gallery;
                $errore=$error;
            }
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


