<?php

require_once ('ConfSmarty.php');
class VRegistrazioneProprietario
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
     * Funzione che comunica a smarty di mostrare la form di registrazione
     * @param $errore da mostrare nella form (quando i parametri non sono corretti)
     */

    public function mostraFormRegistrazioneProp($errore){
        //comunica a smarty di mostrare la form di registrazione
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/registration_owner.tpl');
    }
    /**
     * Funzione per recuperare i dati dalla form di registrazione
     * @return $dati array associativo
     */
    public function recuperaDatiProp(){
        $dati= array();
        if(isset($_POST['username'])){
            $dati['username'] = $_POST['username'];
        }
        if(isset($_POST['password'])){
            $dati['password'] = $_POST['password'];
        }
        if(isset($_POST['email'])){
            $dati['email'] = $_POST['email'];
        }
        if(isset($_POST['PIVA'])){
            $dati['PIVA'] = $_POST['PIVA'];
        }
        return $dati;

    }

}