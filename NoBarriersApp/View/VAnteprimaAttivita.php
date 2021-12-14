<?php

require_once ('ConfSmarty.php');
class VAnteprimaAttivita
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
     * Metodo che mostra l' attività
     * @param EAttivita da mostrare
     */
    public function mostraAnteprima($attivita)
    {

        $gallery = $attivita->getImmagini();
        foreach ($gallery as $g) {
            $g->setData(base64_encode($g->getData()));
        }
      //  $attivita->setImmagini($gallery);
         $imgprincipale=$gallery[0];
          $immagini=array_slice($gallery,1,4);
          $this->smarty->assign("imgprincipale",$imgprincipale);
          $this->smarty->assign("immagini",$immagini);
        $this->smarty->assign("attivita", $attivita);
        $this->smarty->display("smarty/templates/anteprimaattivita.tpl");
    }
    public function mostraAnteprimaModifica($attivita)
    {
        $gallery = $attivita->getImmagini();
        foreach ($gallery as $g) {
            $g->setData(base64_encode($g->getData()));
        }
        //  $attivita->setImmagini($gallery);
        $imgprincipale=$gallery[0];
        $immagini=array_slice($gallery,1,4);
        $this->smarty->assign("imgprincipale",$imgprincipale);
        $this->smarty->assign("immagini",$immagini);
        $this->smarty->assign("attivita", $attivita);
        $this->smarty->display("smarty/templates/anteprimamodifica.tpl");
    }
}