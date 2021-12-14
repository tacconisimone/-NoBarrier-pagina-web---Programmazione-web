<?php

require_once 'include.php';
class CVisitatore
{
    /**
     * Metodo per gestire la modifica profilo del visitatore
     * 1) Se la richiesta è GET, mostriamo la form di ModificaProfilo (se l'utente non è loggato redirect alla form di login)
     * 2) Se la richiesta è POST richiamiamo il metodo Modifica_Visitatore()
     */
    static function ModificaProfiloVisitatore(){
        if($_SERVER['REQUEST_METHOD']=="GET"){

            if(CSession::isLoggedUtente()){
                $view = new VModificaProfiloVisitatore();
                $view->mostraModificaProfilo(CSession::getUtente(),"");
            } else {
                //redirect alla form di login
                header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
            }
        }
        else if($_SERVER['REQUEST_METHOD']=="POST"){
            if(CSession::isLoggedUtente()){

                CVisitatore::Modifica_Visitatore();

            } else {

                //redirect alla form di login
                header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
            }
        }
        else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }

    }


    /**
     * Metodo che gestisce la modifica profilo del visitatore
     */
    static function Modifica_Visitatore()
    {
        $utente=CSession::getUtente();  // recupero l' oggetto visitatore dai dati di sessione
        $username=$utente->getUserName();  //salvo la username iniziale
        $password=$utente->getPass();
        $view = new VModificaProfiloVisitatore();
        $dati=$view->recuperaDatiVisit();   //salvo i dati inseriti nel form di modifica su un array
        $errore=EVisitatore::validaInputModifica($dati,$username,$password);
        if($errore){
            $view->mostraModificaProfilo($utente,$errore);
        }
        else {
            $pm = FPersistentManager::getInstance();
            $utente->setNome($dati['nome']);     // modifico gli attributi del visitatore
            $utente->setCognome($dati['cognome']);
            $utente->setUserName($dati['username']);
            $utente->setEmail($dati['email']);
            $utente->setPass($dati['password']);

            $esito = $pm->updateDiUtente($utente);  // aggiorno il database con i nuovi attributi (del visitatore con quell' id)

            if($esito){    // se l' aggiornamento ha avuto successo prende il visitatore aggiornato dal db e lo logga
                CSession::setUtenteLoggato($utente); //aggiorno l'oggetto utente
                header('Location: /NoBarriersApp/homepage/Profilo');  //MOSTRA IL PROFILO AGGIORNATO
            } else {
                $viewerr = new VErrore();
                $viewerr->mostraErrore("Errore in fase di modifica");
            }
        }

    }
    /**
     * Modifica della foto profilo del visitatore
     * Se l' utente non è loggato (sessione scaduta) redirect al login
     * Se la dimensione dell' immagine supera i 5M messaggio di errore
     * Se l' update non ha successo diverso messaggio di errore
     */
    static function ModificaFotoVisitatore()
    {
        if (CSession::isLoggedUtente()) {      // è suficiente dimostrare che l'utente sia loggato non serve verificare che si tratti di un visitatore
            $pm = FPersistentManager::getInstance();
            $utente = CSession::getUtente();
            $view = new VProfiloVisitatore();
            $fotoobj = $view->recuperaFoto();
            $foto = $fotoobj[0];
            $errore = $fotoobj[1];
            if ($errore== null) {
                $foto->setIdoggetto($utente->getId());
                $esito = $pm->updateFotoVisitatore($foto);
                if ($esito) {
                    $ut = $pm->load("EVisitatore", $utente->getId());
                    CSession::setUtenteLoggato($ut); //aggiorno l'oggetto utente
                    header('Location: /NoBarriersApp/homepage/Profilo');   // REDIRECT AL PROFILO DELL' UTENTE
                } else {
                    $viewerr = new VErrore();
                    $viewerr->mostraErrore("Errore in fase di modifica");
                }
            }
            else {
                $viewerr = new VErrore();
                $viewerr->mostraErrore($errore);
            }
        }
        else {
            header('Location: /NoBarriersApp/autenticazione/Login');   // REDIRECT AL Login
        }
    }

    /**
     * @param $idattivita id dell' attività  da valutare
     * Se l' utente non è loggato come visitatore o non è loggato affatto restituisce un messaggio di errore,altrimenti mostra il form per
     * la valutazione
     */
static function AggiungiValutazione($idattivita){
    if ($_SERVER['REQUEST_METHOD'] == "GET") {

        if (CSession::isLoggedUtente()) {
            $utente = CSession::getUtente();
            if (get_class($utente) == 'EVisitatore') {
                $view = new VValutazione();
                $view->mostraFormValutazione($idattivita, "");
            }
            else {
                $view = new VErrore();    // succede questo solo se l' utente è loggato come proprietario
                $view->mostraErrore("Solo un utente loggato come visitatore può dare una valutazione,registrati se non hai un account o effettua il login");

            }
        }else {
                $view = new VErrore();    // succede questo solo se l' utente è loggato come proprietario
                $view->mostraErrore("Solo un utente loggato come visitatore può dare una valutazione,registrati se non hai un account o effettua il login");

            }
    }
    else if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (CSession::isLoggedUtente()) {
            $utente = CSession::getUtente();
            if (get_class($utente) == 'EVisitatore') {
                static::Valuta($idattivita);
            }
            else {
                $view = new VErrore();    // succede questo solo se l' utente è loggato come proprietario
                $view->mostraErrore("Solo un utente loggato come visitatore può dare una valutazione,registrati se non hai un account o effettua il login");

            }
        }
            else {
                $view = new VErrore();    // succede questo solo se l' utente è loggato come proprietario
                $view->mostraErrore("Solo un utente loggato come visitatore può dare una valutazione,registrati se non hai un account o effettua il login");
            }

    }
    else {
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, POST');
    }

    }


    /**
     * Metodo per fare una valutazione ovvero scrivere un commento e dare un voto
     * se l' utente non è loggato oppure è loggato come amministratore o proprietario viene mostrato il form della valutazione con un messaggio di errore
     */

    static function Valuta($idattivita)
    {
        $utente = CSession::getUtente();
        $idutente=$utente->getId();
        $pm=FPersistentManager::getInstance();
        $view=new VValutazione();
        $newval=$view->recuperaValutazione();
        $val=new EValutazione($newval['testo'],$newval['voto'],$idattivita,$idutente,$newval['data'],$newval['ora']);
        $idvalutazione=$pm->store($val);
        if($idvalutazione) {
            $val->setId($idvalutazione);
            header('Location: /NoBarriersApp/attivita/DescrizioneAttivita/'.$idattivita);
        }
        else {
            $view->mostraFormValutazione($idattivita,"ERRORE! La tua valutazione non è stata correttamente registrata, riprova ");
        }

    }




}