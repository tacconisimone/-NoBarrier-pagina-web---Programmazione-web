<?php

require_once ('ConfSmarty.php');
class VErrore
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
    public function mostraErrore($messaggio){
        $this->smarty->assign('messaggio',$messaggio);
        $this->smarty->display('smarty/templates/errore.tpl');
    }

}