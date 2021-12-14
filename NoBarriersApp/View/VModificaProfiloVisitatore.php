<?php

require_once ('ConfSmarty.php');
class VModificaProfiloVisitatore
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
     * Funzione che mostra la form di ModificaProfilo del visitatore
     */
    public function mostraModificaProfilo($utente,$errore){

        $this->smarty->assign('utente',$utente);
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/modify_visit_profile.tpl');

    }
    /**
     * Funzione per recuperare i dati dalla form di modifica del profilo
     * @return $dati array associativo
     */
    public function recuperaDatiVisit(){
        $dati= array();
        if(isset($_POST['username'])){
            $dati['username'] = $_POST['username'];
        }
        if(isset($_POST['password'])){
            $dati['password'] = $_POST['password'];
        }
        if(isset($_POST['nome'])){
            $dati['nome'] = $_POST['nome'];
        }
        if(isset($_POST['email'])){
            $dati['email'] = $_POST['email'];
        }
        if(isset($_POST['cognome'])){
            $dati['cognome'] = $_POST['cognome'];
        }
        return $dati;

    }


}