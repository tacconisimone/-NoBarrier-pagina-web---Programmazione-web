<?php

require_once ('ConfSmarty.php');
class VDettaglioAttivita
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
    public function mostraAttivita($attivita, $arrvalutazioni,$utente,$errore)
    {
        if (count($arrvalutazioni) == 0)
            $this->smarty->assign('media_voto', 0);
        else
            $this->smarty->assign('media_voto', $attivita->averageMark());

        $gallery = $attivita->getImmagini();
        foreach ($gallery as $g) {
            $g->setData(base64_encode($g->getData()));
        }
        //$attivita->setImmagini($gallery);
            $imgprincipale=$gallery[0];
            $immagini=array_slice($gallery,1,4);
            $this->smarty->assign("imgprincipale",$imgprincipale);
            $this->smarty->assign("immagini",$immagini);
            $this->smarty->assign("errore", $errore);
            $this->smarty->assign("attivita", $attivita);
            $this->smarty->assign("valutazioni", $arrvalutazioni);
            $this->smarty->assign("utente",$utente);
            $this->smarty->display("smarty/templates/dettagli_attivita.tpl");


    }






}