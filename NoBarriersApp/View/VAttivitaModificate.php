<?php

require_once ('ConfSmarty.php');
class VAttivitaModificate
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
    public function mostraAttivita($attivita){
        $this->smarty->assign('attivita',$attivita);
        $this->smarty->display('smarty/templates/listactivitymodified.tpl');
    }


}