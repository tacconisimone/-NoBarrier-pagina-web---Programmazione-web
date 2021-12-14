<?php

require_once ('ConfSmarty.php');
class VProfiloVisitatore
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
    public function recuperaFoto(){
        $tempname = $_FILES['immagine']['tmp_name'];   // di tratta di un array superglobale associativo, 'immagine' è l' indice, mentre 'tmp_name' è il campo del sottoarray a cui si vuole accedere
        $size = $_FILES['immagine']['size'];
        $typefotop = $_FILES['immagine']['type'];
        $max_size = 5242880; //dimensione massima in byte consentita
        if (is_uploaded_file($tempname) && static::check_ext($typefotop) && $size <= $max_size) {
            $foto = file_get_contents($tempname);
            $foto = addslashes($foto);
            $fotoobj = new EImmagine($foto, $typefotop);
            $errore="";
            $fotonuova=array($fotoobj,$errore);
            return $fotonuova;
        }
        else{
            $fotoobj=null;
            $errore="l'immagine non può superare i 5M";
            $fotonuova=array($fotoobj,$errore);
            return $fotonuova;
        }
    }
//$_FILES["upload"]["tmp_name"]: percorso e il nome del file temporaneo sul server;
//$_FILES["upload"]["type"]: tipo di file caricato (in formato MIME type);
    /**
     * Funzione che mostra il profilo dell'utente
     * @param $utente da mostrare
     */
    public function mostraProfilo($utente){
        if($utente!=null){
            $img=$utente->getImmagine();
            $img->setData(base64_encode($img->getData()));
            $utente->setImmagine($img);
        }

        $this->smarty->assign('utente', $utente);
        $this->smarty->display('smarty/templates/ProfiloVisitatore.tpl');

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