<?php

require_once ('ConfSmarty.php');
class VNuovaCategoria
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
    public function inseriscicategoria($errore){
        $this->smarty->assign('errore',$errore);
        $this->smarty->display('smarty/templates/insert_category.tpl');
    }
    public function recuperaCategoria(){
        $categoria=null;
        if(isset($_POST['categoria'])){
             $categoria= $_POST['categoria'];
        }
        return $categoria;
    }

}