<?php

require_once ('ConfSmarty.php');
class VListaAttivita
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
    public function mostraAttivita($listaattivita,$utente){
        $this->smarty->assign('listaattivita',$listaattivita);
        $this->smarty->assign('utente',$utente);
        $this->smarty->display("smarty/templates/list_activity.tpl");

    }

}