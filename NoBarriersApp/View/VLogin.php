<?php

require_once ('ConfSmarty.php');
class VLogin
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
     * Funzione che comunica a Smarty di mostrare lo form di login, comunicando se è Login utente(visitatore/proprietario) o amministratore
     * @param $ruolo dell'utilizzatore dell'app
     */
    public function mostraFormLogin($ruolo,$errore){

        $this->smarty->assign('ruolo',$ruolo);
        $this->smarty->assign('errore', $errore);
        $this->smarty->display("smarty/templates/login.tpl");

    }
    /**
     * Funzione per recuperare le credenziali dell'utente/admin per il login
     * @return array
     */
    public function recuperaCredenziali(){
        $credenziali = array();
        if(isset($_POST['username']) && isset($_POST['password'])){
            $credenziali['username'] =  $_POST['username'];
            $credenziali['password'] =  $_POST['password'];
        }
        return $credenziali;
    }

}