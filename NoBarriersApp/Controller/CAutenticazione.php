<?php

require_once 'include.php';
class CAutenticazione
{
    /**
     * Metodo che implementa il caso d'uso di login. Se richiamato tramite GET, fornisce
     * la pagina di login, se richiamato tramite POST cerca di autenticare l'utente attraverso
     * i valori che quest'ultimo ha fornito.
     * Se l' utente è loggato redirect alla homepage
     */
    static function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            if (CSession::isLoggedUtente()) {      // se il metodo è GET e l' utente è loggato torna alla home page
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                $referer = $_SERVER['HTTP_REFERER']; //indirizzo che stavo visitando
                $loc = substr($referer, strpos($referer, "/NoBarriersApp")); //estrapolo la parte path della pagina che stavo visitando
                CSession::setPath($loc); //salvo nei dati di sessione il path che stavo visitando
                $view = new VLogin();
                $view->mostraFormLogin("utente","");
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {  // se il metodo è POST e l' utente è loggato torna alla home page

            if (CSession::isLoggedUtente()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                CAutenticazione::Entra();

            }

        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }

    }

    /**
     * Metodo che gestisce il login dell'utente
     */
    static function Entra()
    {
        $view = new VLogin();
        $pm = FPersistentManager::getInstance();
        $credenziali = $view->recuperaCredenziali();   // recupero le credenziali inserite ma la password ha l' hash
        if($pm->esisteUsername($credenziali['username'])){
        $user=$pm->getUtenteByUsername($credenziali['username']); // recupero l'utente dal db (oggetto)
        $password=$user->getPass(); // memorizzo l' hash della password
        $verify=password_verify($credenziali['password'],$password); // torna un booleano dal confronto tra (string $password, string $hash)
            if ($verify) {
                CSession::setUtenteLoggato($user);
                $location = CSession::getPath(); //recupero il path salvato precedentemente
                CSession::removePath(); //cancello il path dai dati di sessione
                header('Location: '.$location); //redirect alla location precedente
            }
            else {                                        // se l' utente non è presente nel db mostra un messaggio di errore
                $viewerr = new VLogin();
                $viewerr->mostraFormLogin("utente","password errata!");
            }
        } else {                                        // se l' utente non è presente nel db mostra un messaggio di errore
            $viewerr = new VLogin();
            $viewerr->mostraFormLogin("utente","username o password errati!");
        }

    }


    /**
     * Metodo per avviare la registrazione del visitatore
     * 1) Se la richiesta è GET, mostriamo la form di registrazione (se l'utente è loggato redirect alla home page)
     * 2) Se la richiesta è POST richiamiamo il metodo Registra_Visit()
     *
     */
    static function RegistrazioneVisitatore()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            if (CSession::isLoggedUtente()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                $view = new VRegistrazioneVisitatore();
                $errore = "";
                $view->mostraFormRegistrazioneVisit($errore);   // IMPLEMENTARE METODO
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (CSession::isLoggedUtente()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                CAutenticazione::Registra_Visit();
            }

        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }



    }

    /**
     * Metodo che gestisce la registrazione del visitatore
     *
     */
    static function Registra_Visit()
    {
        $view = new VRegistrazioneVisitatore();
        $dati = $view->recuperaDatiVisit();
        $errore = EVisitatore::validaInput($dati);  // tutti i metodi di validazione sono statici(non esiste ancora l' oggetto visitatore)
        if ($errore) {
            $view->mostraFormRegistrazioneVisit($errore);
        } else {
            $visit = new EVisitatore($dati['username'], $dati['password'], $dati['nome'], $dati['email'], $dati['cognome']);
            $visit->hashPassword(); // cifratura della password
            $pm = FPersistentManager::getInstance();
            $id = $pm->store($visit);
        }
        if ($id) {
            //redirect alla form di login
            header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
        } else {
            $viewerr = new VErrore();
            $viewerr->mostraErrore("Errore in fase di registrazione");
        }


    }
    /**
     * Metodo per avviare la registrazione del proprietario
     * 1) Se la richiesta è GET, mostriamo la form di registrazione (se l'utente è loggato redirect alla home page)
     * 2) Se la richiesta è POST richiamiamo il metodo Registra_Prop()
     *
     */

    static function RegistrazioneProprietario()
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {

            if (CSession::isLoggedUtente()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                $view = new VRegistrazioneProprietario();
                $errore = "";
                $view->mostraFormRegistrazioneProp($errore);   // IMPLEMENTARE METODO
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (CSession::isLoggedUtente()) {
                //redirect alla home page
                header('Location: /NoBarriersApp/homepage/Homepage');
            } else {
                CAutenticazione::Registra_Prop();
            }

        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, POST');
        }

    }
    /**
     * Metodo che gestisce la registrazione del proprietario
     *
     */

    static function Registra_Prop()
    {
        $view = new VRegistrazioneProprietario();
        $dati = $view->recuperaDatiProp();
        $errore = EProprietario::validaInput($dati);
        if ($errore) {
            $view->mostraFormRegistrazioneProp($errore);
        } else {
            $pro = new EProprietario($dati['username'], $dati['password'], $dati['email'], $dati['PIVA']);
            $pro->hashPassword();  // prima di salvare sul database cifro la password
            $pm = FPersistentManager::getInstance();
            $id = $pm->store($pro);
        }
        if ($id) {
            //redirect alla form di login
            header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
        } else {
            $viewerr = new VErrore();
            $viewerr->mostraErrore("Errore in fase di registrazione");
        }

    }
    // NB. La store sul db viene fatta solo in fase di registrazione, mentre l' utente viene salvato nelle variabili di sessione con il login
    // per questo se la registrazione ha successo si fa il redirect al login
    // si è scelto di inserire tutti i metodi di validazione dell' input nelle classi Entity, la view recupera solo i dati
    /**
     * Metodo per effettuare il logout
     * Se l'utente è loggato redirect alla homepage
     * Se l'utente non è loggato redirect alla homepage
     */
    static function Logout(){
        if(CSession::isLoggedUtente()){
            CSession::logout(); //cancello i dati di sessione
        }
        // redirect alla homepage in entrambi i casi

        header('Location: /NoBarriersApp/homepage/Homepage');

    }



    /**
     * Metodo per gestire l'eliminazione dell'utente
     * (solo utente loggato)
     * Se l' utente non è loggato redirect al login
     * @param $idutente da eliminare
     */

    static function EliminaUtente(){
        if(CSession::isLoggedUtente()){

            $utente = CSession::getUtente();
            $class=get_class($utente);
            $idutente = $utente->getId();

            $pm = FPersistentManager::getInstance();

            $final = $pm->delete($class,$idutente);
            if($final){
                //rimozione corretta, redirect alla pagina home
                static::Logout();

            }
            else {

                $viewerr = new VErrore();
                $viewerr->mostraErrore("Rimozione utente non riuscita");
            }
        } else { //utente non loggato redirect a login
            header('Location: /NoBarriersApp/autenticazione/Login');   // REDIRECT AL LOGIN

        }

    }


}