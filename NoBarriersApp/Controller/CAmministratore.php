<?php

require_once 'include.php';
class CAmministratore
{
    /**
     * Metodo per avviare il login amministratore
     * 1) Se la richiesta è GET, mostriamo la form di Login (se l'admin è loggato redirect alla home page)
     * 2) Se la richiesta è POST richiamiamo il metodo Entra()
     */
    static function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            if (CSession::isLoggedAdmin()) {
                //redirect alla home page dell' amministratore
                header('Location: /NoBarriersApp/homepage/Homepage'); //REDIRECT ALLA HOMEPAGE DELL' AMMINISTRATORE
            } else {
                $view = new VLogin();
                $view->mostraFormLogin("amministratore", "");
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (CSession::isLoggedAdmin()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage'); // REDIRECT ALLA HOMEPAGE DELL' AMMINISTRATORE
            } else {
                CAmministratore::Entra();
            }

        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }

    }

    /**
     * Metodo che gestisce il login dell'amministratore
     * L'amministratore entra con username=amministratore e password=nobarriersappweb
     */
    static function Entra()
    {
        $view = new VLogin();
        $credenziali = $view->recuperaCredenziali();
        if ($credenziali['username'] == 'amministratore' && $credenziali['password'] == 'nobarriersappweb') {
            //login admin avvenuto con successo, salvataggio nei dati di sessione
            CSession::setAdminLoggato();

            //login avvenuto con successo, mostrare la pagina principale dell'amministratore
            header('Location: /NoBarriersApp/homepage/Homepage');
        } else {
            //username e/o password errati, mostrare login con errore
            $viewerr = new VLogin();
            $viewerr->mostraFormLogin("amministratore", "Username e/o password errati");
        }
    }

    /** Metodo per effettuare il logout
     * Se l'utente è loggato redirect alla homepage
     * Se l'utente non è loggato redirect alla homepage
     * */

    static function Logout()
    {
        if (CSession::isLoggedAdmin()) {
            CSession::logout(); //cancello i dati di sessione
        }
        // redirect alla homepage in entrambi i casi
        header('Location: /NoBarriersApp/homepage/Homepage');  // REDIRECT ALL' HOMEPAGE UTENTE

    }

    /**
     * @param $id dell' attività di cui si vuole mostrare un'anteprima
     * L' attività era stata memorizzata sul database con l' attributo
     * visibility=0, quindi questa anteprima è visibile solo all' amministratore.
     * Se l' amministratore non è loggato restituisce un messaggio di errore
     */

    static function AnteprimaAttivita($id)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->load('EAttivita', $id);
            $view = new VAnteprimaAttivita();
            $view->mostraAnteprima($attivita);
        } else {
            $view = new VErrore();
            $view->mostraErrore("Devi essere un amministratore per accedere all' area riservata");
        }
    }

    /**
     * @param $id dell' attivita da approvare
     * Se l' amministratore è loggato può pubblicare l' attività
     * settando l' attributo visibility=1. Se l' amministratore non è loggato redirect al login amministratore
     */

    static function ApprovazioneAnnuncio($id)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $esito = $pm->update(FAttivita, $id, 'visibility', 1);
            if ($esito) {
                header('Location: /NoBarriersApp/homepage/Homepage'); // REDIRECT ALLA HOMEPAGE DELL' AMMINISTRATORE
            } else {
                $view = new VErrore();
                $view->mostraErrore("La pubblicazione non ha avuto successo!");
            }
        } else {
            header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
        }
    }

    /**
     * @param $id dell' attività che non è stata valutata idonea.
     * Se l' amministratore è loggato può decidere di non pubblicare una attività dopo averne visto l'anteprima,
     * questo comporta l' eliminazione dell' attività in questione dal database.Se l' amministratore non è loggato redirect al login amministratore
     */

    static function AttivitaNonApprovata($id)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $esito = $pm->delete('EAttivita', $id);
            if ($esito) {
                header('Location: /NoBarriersApp/homepage/Homepage'); // REDIRECT ALLA HOMEPAGE DELL' AMMINISTRATORE
            } else {
                $view = new VErrore();
                $view->mostraErrore("L'eliminazione non ha avuto successo!");
            }
        }
        else {
            header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
        }
    }

    /**
     * L' amministratore può aggiungere nuove categorie consentendo all' applicazione di crescere.
     * Se la richiesta è di tipo GET viene mostrato il form per inserire una nouova categoria.
     * Se la richiesta è di tipo POST viene richiamato il metodo AggiungiCategoria()
     * Se la richiesta non è ne GET e ne POST , allora si verifica un errore 405
     * Se l' amministratore non è loggato viene rediretto al login amministratore
     */

    static function NuovaCategoria()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (CSession::isLoggedAdmin()) {
                $view = new VNuovaCategoria();
                $view->inseriscicategoria("");
            } else {
                header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (CSession::isLoggedAdmin()) {
                static::AggiungiCategoria();
            } else {
                header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
            }
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }
    }


    static function AggiungiCategoria()
    {
        $view = new VNuovaCategoria();
        $cat = $view->recuperaCategoria();  // si tratta del nome ovvero di una stringa
        $esito = ECategoria::validaCategoria($cat);
        if ($esito) {
            $categoria = new ECategoria($cat);
            $pm = FPersistentManager::getInstance();
            $idcat = $pm->store($categoria);
            $categoria->setId($idcat);
            header('Location: /NoBarriersApp/homepage/Homepage');
        } else {
            $vista = new VNuovaCategoria();
            $vista->inseriscicategoria("La categoria inserita è già presente!");
        }
    }

    /**
     * L' amministratore può aggiungere nuove città consentendo all' applicazione di crescere.
     * Se la richiesta è di tipo GET viene mostrato il form per inserire una nouova città.
     * Se la richiesta è di tipo POST viene richiamato il metodo AggiungiCitta()
     * Se la richiesta non è ne GET e ne POST , allora si verifica un errore 405
     * Se l' amministratore non è loggato viene rediretto al login amministratore
     */

    static function NuovaCitta()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (CSession::isLoggedAdmin()) {
                $view = new VNuovaCitta();
                $view->inseriscicitta("");
            } else {
                header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (CSession::isLoggedAdmin()) {
                static::AggiungiCitta();
            } else {
                header('Location: /NoBarriersApp/amministratore/Login'); // REDIRECT AL LOGIN AMMINISTRATORE
            }
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }
    }


    static function AggiungiCitta()
    {
        $view = new VNuovaCitta();
        $cit = $view->recuperaCitta();  // si tratta del nome ovvero di una stringa
        $esito = ECitta::validaCitta($cit);
        if ($esito) {
            $citta = new ECitta($cit);
            $pm = FPersistentManager::getInstance();
            $idcit = $pm->store($citta);
            $citta->setId($idcit);
            header('Location: /NoBarriersApp/homepage/Homepage');
        } else {
            $vista = new VNuovaCitta();
            $vista->inseriscicitta("La città inserita è già presente!");
        }
    }

    /**
     * Se l' amministratore è loggato può vedere la lista delle attività che sono state modificate dall' utente proprietario
     * Di una stessa attività possono essere stati modificati solo descrizione e indirizzo, solo foto oppure descrizione, indirizzo e foto.
     * Nella lista delle attività è presente una descrizione breve, che include nome,citta e categoria
     * dell' attività che è stata modificata.
     * Se l' amministratore non è loggato mostra un errore di accesso non consentito
     */

    static function ListaAttivitaModificate()
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attmodificate = $pm->AttivitaModificate();   //DA AGGIUNGERE  è un array di oggetti EATTIVITAMODIFICATA
            $immagini = $pm->ImmaginiModificate();   //ARRAY DI OGGETTI EIMMAGINE
            if (empty($attmodificate) && empty($immagini)) {
                $view = new VMessaggio();
                $view->mostramessaggio("Non è stata modificata nessuna attività nelle ultime ore");
            } else {
                $attobj = EAttivita::ListaAttivitaModificate($attmodificate, $immagini); // array di oggetti EAttivita
                foreach ($attobj as $a) {
                    $tmp = array(
                        'id' => $a->getId(),                       // id di EAttivita
                        'attivita' => $a->getNome(),
                        'citta' => $a->getCitta(),
                        'categoria' => $a->getCategoria()
                    );
                    $arrayattivita[] = $tmp;
                }
                $view = new VAttivitaModificate();
                $view->mostraAttivita($arrayattivita);
            }
        } else {
            $view = new VErrore();
            $view->mostraErrore("Accesso non consentito");
        }
    }

    /**
     * @param $idattivita che è stata modificata.
     * Cliccando sul nome dell' attività dalla lista delle attività modificate l' amministratore se è loggato può visualizzarne
     * un' anteprima. Questa anteprima è uguale a quella che l' amministratore vede in fase di pubblicazione.
     * Se l' amministratore non è loggato mostra un messaggio di errore
     */
    static function AnteprimaModifica($idattivita)
    {      // si tratta dell' id di un oggetto EAttivita
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->AttivitaModificate();
            $immagini = $pm->ImmaginiModificate();
            $tipo = EAttivita::TipoDiModificaAttivita($attivita, $immagini, $idattivita);
            if ($tipo == 'comuni') {
                static::modificaAttivitaFoto($idattivita);

            } else if ($tipo == 'attivita') {
                static::ModificaDatiAttivita($idattivita);
            } else {
                static::ModificaImmagini($idattivita);

            }

        } else {
            $view = new VErrore();
            $view->mostraErrore("Accesso ad un' area non consentita");
        }

    }

    /**
     * @param $idattivita  id dell' attività che è stata modificata
     * Se l' amministratore è loggato mostra l' anteprima di un' attività in cui sono stati modificati descrizione, indirizzo e foto.
     * Se l' amministratore non è loggato, segue un messaggio di errore
     */

    static function modificaAttivitaFoto($idattivita)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->load('EAttivita', $idattivita);
            $dati = $pm->loadModificataByIdAtt($idattivita);   //oggetto EAttivitamodificate ricavato da EAttivita
            $immagini = $pm->GalleriaModificate($idattivita);  //oggetto Eimmagini ricavato da EAttivita (sono quelle modificate)
            $attivita->setDescrizione($dati->getDescrizione());
            $attivita->setIndirizzo($dati->getIndirizzo());
            $attivita->setImmagini($immagini);
            $view = new VAnteprimaAttivita();
            $view->mostraAnteprimaModifica($attivita);
        } else {
            $view = new VErrore();
            $view->mostraErrore("Attenzione! Accesso non consentito");
        }
    }
    /**
     * @param $idattivita  id dell' attività che è stata modificata
     * Se l' amministratore è loggato mostra l' anteprima di un' attività in cui sono stati modificati descrizione e indirizzo.
     * Se l' amministratore non è loggato, segue un messaggio di errore
     */

    static function ModificaDatiAttivita($idattivita)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->load('EAttivita', $idattivita);
            $dati = $pm->loadModificataByIdAtt($idattivita);
            $attivita->setDescrizione($dati->getDescrizione());
            $attivita->setIndirizzo($dati->getIndirizzo());
            $view = new VAnteprimaAttivita();
            $view->mostraAnteprimaModifica($attivita);
        } else {
            $view = new VErrore();
            $view->mostraErrore("Attenzione! Accesso non consentito");
        }
    }
    /**
     * @param $idattivita  id dell' attività che è stata modificata
     * Se l' amministratore è loggato mostra l' anteprima di un' attività in cui sono state modificate le foto.
     * Se l' amministratore non è loggato, segue un messaggio di errore
     */

    static function ModificaImmagini($idattivita)
    {
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->load('EAttivita', $idattivita);
            $immagini = $pm->GalleriaModificate($idattivita);
            $attivita->setImmagini($immagini);
            $view = new VAnteprimaAttivita();
            $view->mostraAnteprimaModifica($attivita);
        } else {
            $view = new VErrore();
            $view->mostraErrore("Attenzione! Accesso non consentito");
        }
    }

    /**
     * @param $idatt che si intende modificare.
     * Se l' amministratore è loggato questo metodo realizza l'update dell' attività che è stata modificata.
     * Se l' amministratore non è loggato, segue un messaggio di errore
     */

    static function EseguiModifica($idatt)     // $idatt= $id di EAttivita
    {

       if (CSession::isLoggedAdmin()){
        $pm = FPersistentManager::getInstance();
        $attmodif = $pm->loadModificataByIdAtt($idatt);   //prendo l' oggetto EAttivitamodificata dal db
        $imgmod = $pm->loadImgModifiedByIdAtt($idatt);  // prendo gli oggetti EImmagine relativi alle immagini modificate dal db
        if (!empty($attmodif) && !empty($imgmod)) { // se i due array sono vuoti
            $esito1 = $pm->UpdateDatiAttivita($attmodif);
            $esito2 = $pm->UpdateImmaginiAttivita($imgmod);
            $esito = array($esito1, $esito2);
            if (empty($esito)) {
                  $view = new VErrore();
                  $view->mostraErrore("L'operazione non è riuscita con successo");
            }
            else {
                $pm->deleteimgModifiedByIdAtt($idatt);
                $pm->deleteAttModifiedByIdAtt($idatt);
                header('Location: /NoBarriersApp/amministratore/ListaAttivitaModificate');
            }
        } else if (empty($imgmod) && !empty($attmodif)) {
             $esito1 = $pm->UpdateDatiAttivita($attmodif);
             if (empty($esito1)) {
                 $view = new VErrore();
                 $view->mostraErrore("L'operazione non è riuscita con successo");
            }
             else{
                 $pm->deleteAttModifiedByIdAtt($idatt);
                 header('Location: /NoBarriersApp/amministratore/ListaAttivitaModificate');

             }
        } else {
            $esito2 = $pm->UpdateImmaginiAttivita($imgmod);
            if (empty($esito2)) {
                $view = new VErrore();
                $view->mostraErrore("L'operazione non è riuscita con successo");
            }
            else{
                $pm->deleteimgModifiedByIdAtt($idatt);
                header('Location: /NoBarriersApp/amministratore/ListaAttivitaModificate');
            }
        }

    }
        else {
            $view = new VErrore();
            $view->mostraErrore("Operazione non consentita");

        }

    }

    /**
     * @param $idattivita id dell' attività le cui modifiche non sono state approvate dall' amministratore
     * Se l' amministratore è loggato può procedere con l' eliminazione, altrimenti compare un messaggio di errore
     * Se la delete non va a buon fine compare un altro messaggio di errore
     */

    static function EliminaModifiche($idattivita)
    {      // si tratta dell' id di un oggetto EAttivita
        if (CSession::isLoggedAdmin()) {
            $pm = FPersistentManager::getInstance();
            $attivita = $pm->AttivitaModificate();
            $immagini = $pm->ImmaginiModificate();
            $tipo = EAttivita::TipoDiModificaAttivita($attivita, $immagini, $idattivita);
            if ($tipo == 'comuni') {
               $esito1=$pm->deleteAttModifiedByIdAtt($idattivita);
               $esito2=$pm->deleteimgModifiedByIdAtt($idattivita);
               $esito=$esito1&&$esito2;

            } else if ($tipo == 'attivita') {
                $esito=$pm->deleteAttModifiedByIdAtt($idattivita);
            } else {
                $esito=$pm->deleteimgModifiedByIdAtt($idattivita);

            }
            if($esito){
                header('Location: /NoBarriersApp/amministratore/ListaAttivitaModificate');
            }
            else{
                $view=new VErrore();
                $view->mostraErrore("L' eliminazione delle modifiche non è andata a buon fine!");
            }

        } else {
            $view = new VErrore();
            $view->mostraErrore("Accesso ad un' area non consentita");
        }

    }



    }







