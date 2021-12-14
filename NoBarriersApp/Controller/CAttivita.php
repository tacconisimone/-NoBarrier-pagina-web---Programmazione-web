<?php
error_reporting(~E_ALL);
require_once 'include.php';
class CAttivita
{

    /**
     * @param $id dell' attività selezionata dalla lista delle attività (l' attività si trova in una certa regione e categoria)
     * Questo metodo permette di visualizzare i dettagli dell'attività scelta dall' utente ed i commenti ad essa relativi.
     * Un utente non deve essere registrato per visualizzare la descrizione di un' attività
     *
     */

    static function DescrizioneAttivita($id){
        $pm=FPersistentManager::getInstance();
        $attivita=$pm->load('EAttivita',$id); //prendo l'oggetto attività grazie all' id tratto dal tpl list_activity, l' atività al suo interno contiene anche la galleria di immagini e le valutazioni
        $valutazioni=$attivita->getValutazioni(); // prendo l' arrai di oggetti EValutazione dall' oggetto attività
        $arrvalutazioni = array();
        foreach ($valutazioni as $val){
            $id = $val->getIdVisitatore(); //idutente, metodo di EValutazione (solo il visitatore può dare una valutazione)
            $visitatore = $pm->load("EVisitatore",$id); // oggetto EVisitatore che ha valutato
            $img = $visitatore->getImmagine(); //imgprofilo
            $img->setData(base64_encode($img->getData()));
            $tmp = array(
                'visitatore'=>$visitatore->getUserName(),
                'img'=>$img,
                'valutazione'=>$val
            );
            $arrvalutazioni[]=$tmp;

        }
        if($arrvalutazioni==null){
            $errore="Non ci sono valutazioni per questa attività";
        }
        else{
            $errore="";
        }
        $utente=CSession::getUtente();
        $view=new VDettaglioAttivita();
        $view->mostraAttivita($attivita,$arrvalutazioni,$utente,$errore);
    }

    /**
     * @param $idcitta id della città scelta dall'utente nella homepage dalla lista delle città
     * @param $idcategoria id della categoria scelta dall'utente dalla lista delle categorie
     * Questa funzione permette di vedere una lista delle attività (con descrizione breve) corrispondenti ai criteri di ricerca dell' utente
     * per città e categoria
     */

    static function AttivitaPerCittaECategoria($idcitta,$idcategoria)
    {
        $pm=FPersistentManager::getInstance();
        $listaattivita=$pm->ListaAttivita($idcitta,$idcategoria);
        $citta=$pm->load('ECitta',$idcitta);
        $categoria=$pm->load('ECategoria',$idcategoria);
        foreach($listaattivita as $att)
        {
            $gallery=$att->getImmagini();
            $imgprincipale=$gallery[0];
            $imgprincipale->setData(base64_encode($imgprincipale->getData()));
            $tmp=array(
                'id'=>$att->getId(),
                'nome'=>$att->getNome(),
                'imgprincipale'=>$imgprincipale,
                'citta'=>$citta->getNome(),
                'categoria'=>$categoria->getNome(),
                'indirizzo'=>$att->getIndirizzo()
            );

            $arrayattivita[]=$tmp;
        }

        if($arrayattivita==null){
           $vista=new VErrore();
           $vista->mostraErrore("Non ci sono attività per questa città e categoria");
        }
        else{
            $utente=CSession::getUtente();
            $view=new VListaAttivita();
            $view->mostraAttivita($arrayattivita,$utente);
        }


    }

}