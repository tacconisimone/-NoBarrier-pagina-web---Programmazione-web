<?php

require_once ('ConfSmarty.php');
class VValutazione
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
    public function mostraFormValutazione($idattivita,$errore){  //  non ho passato anche $attività come parametro perchè nel form di valutazione richiamato da questo metodo non ci sono attributi dell'attività
        //comunica a smarty di mostrare la form di valutazione
        $this->smarty->assign('idattivita',$idattivita);
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/form_valutation.tpl');
    }
    public function recuperaValutazione(){
        $valutazione['testo'] = $_POST['testo'];
        $valutazione['voto']=$_POST['voto'];
        date_default_timezone_set('CET');
        $data = date ("Y-m-d H:m:s");
        $arr = explode(" ", $data);
        $valutazione['data'] = $arr[0];
        $valutazione['ora'] = $arr[1];
        return $valutazione;
    }


}