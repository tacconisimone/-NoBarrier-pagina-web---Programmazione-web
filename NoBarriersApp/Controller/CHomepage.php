<?php

require_once 'include.php';
class CHomepage
{
    /**
     * Se l' amministratore è loggato mostra la sua homepage altrimenti mostra l' homepage dell'utente che è la stessa
     * per per utente visitatore,proprietario e non loggato
     */
    static function Homepage(){
        $pm=FPersistentManager::getInstance();
        $view=new VHomepage();
        if(CSession::isLoggedAdmin()){
            $attivita=$pm->AttivitaDaApprovare(); //array di oggetti EAttivita con visibility i,postata a false
            foreach ($attivita as $att){
                $gallery = $att->getImmagini(); //galleria di immagini dell' attività
                $imgprincipale=$gallery[0]; // la prima immagine inserita dal proprietario è quella principale
                $imgprincipale->setData(base64_encode($imgprincipale->getData()));
                $citta=$att->getCitta();
                $nomecitta=$citta->getNome();
                $categoria=$att->getCategoria();
                $nomecategoria=$categoria->getNome();
                $tmp = array(
                    'id'=>$att->getId(),     //-----------------------------------------------------------------
                    'attivita'=>$att->getNome(),
                    'imgattivita'=>$imgprincipale,
                    'citta'=>$nomecitta,
                    'categoria'=>$nomecategoria
                );
                $arrayattivita[]=$tmp;
            }
            $view->mostraHomepageAmministratore($arrayattivita);


        }else {
            $listacitta = $pm->TutteLeCitta(); // si tratta di un array di oggetti ECitta(id,nome)
            $utente = CSession::getUtente();
            $view->mostraHomepageUtente($listacitta, $utente);
        }

    }

    /**
     * @param $idcitta id della città scelta dall' utente
     * L' utente può scegliere una categoria solo dopo aver selezionato una città
     */
    static function Categorie($idcitta){
        $pm=FPersistentManager::getInstance();
        $listacategorie=$pm->TutteLeCategorie();
        $view=new VCategorie();
        $utente=CSession::getUtente();
        $view->mostraCategorie($listacategorie,$utente,$idcitta);  //idcitta serve per selezionare l'attivita in base a citta e categoria

    }

    /**
     * Questo metodo permette ad un utente loggato come visitatore o proprietario di accedere al proprio profilo.
     * Se l' utente non è loggato mostra un messaggio di errore
     */
    static function Profilo()
    {
        if (CSession::isLoggedUtente()) {
            $utente = CSession::getUtente();
            if (get_class($utente) == 'EVisitatore') {
                $view = new VProfiloVisitatore();
                $view->mostraProfilo($utente);
            } else {
                $view = new VProfiloProprietario();
                $view->mostraProfilo($utente);
            }
        }
        else {
            $view=new VErrore();
            $view->mostraErrore("Devi essere loggato per accedere al profilo!");
        }
    }

    /**
     * Da informazioni sui creatori dell' applicazione
     */
    static function ChiSiamo(){
        $utente=CSession::getUtente();
        $view =new VHomepage();
        $view->ChiSiamo($utente); // passo l' utente per la navbar
    }

}