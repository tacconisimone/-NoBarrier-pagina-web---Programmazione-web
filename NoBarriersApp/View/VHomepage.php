<?php

require_once ('ConfSmarty.php');
class VHomepage
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
    public function mostraHomepageUtente($citta,$utente)
    {
        $this->smarty->assign('utente',$utente);
        $this->smarty->assign('citta', $citta);
        $this->smarty->display('smarty/templates/homepageuser.tpl');


    }
    public function mostraHomepageAmministratore($attivita)
    {
        $this->smarty->assign('attivita',$attivita);
        $this->smarty->display('smarty/templates/home_admin.tpl');

    }
    public function ChiSiamo($utente){
        $this->smarty->assign('utente',$utente);
        $this->smarty->display('smarty/templates/html/ChiSiamo.html');
    }

}