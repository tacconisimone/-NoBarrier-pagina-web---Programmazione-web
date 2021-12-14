<?php

require_once ('ConfSmarty.php');
class VNuovaCitta
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
    public function inseriscicitta($errore){
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/insert_city.tpl');
    }
    public function recuperaCitta(){
        $citta=null;
        if(isset($_POST['citta'])){
            $citta= $_POST['citta'];
        }
        return $citta;
    }


}