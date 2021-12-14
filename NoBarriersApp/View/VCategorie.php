<?php

require_once ('ConfSmarty.php');
class VCategorie
{
    private $smarty;

    /**
     * Funzione che inizializza e configura smarty.
     */
    public function __construct()
    {
        $this->smarty = ConfSmarty::configuration();
    }
    public function mostraCategorie($categorie,$utente,$idcitta)
    {
        $this->smarty->assign('categorie',$categorie);
        $this->smarty->assign('utente',$utente);
        $this->smarty->assign('idcitta',$idcitta);
        $this->smarty->display('smarty/templates/category.tpl');


    }
}