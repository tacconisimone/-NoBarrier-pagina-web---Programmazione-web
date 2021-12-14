<?php

require_once ('ConfSmarty.php');
class VAttivitaPossedute
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
    public function mostraMieAttivita($utente,$attinbreve,$errore){
        $this->smarty->assign('utente',$utente);
        $this->smarty->assign('attinbreve',$attinbreve);
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/listactivity_owner.tpl');

    }

}