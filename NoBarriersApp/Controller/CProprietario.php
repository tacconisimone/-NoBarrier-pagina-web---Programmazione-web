<?php

require_once 'include.php';
class CProprietario
{

    /**
     * Metodo per gestire la modifica profilo del proprietario
     * 1) Se la richiesta è GET, mostriamo la form di ModificaProfilo (se l'utente non è loggato redirect alla form di login)
     * 2) Se la richiesta è POST richiamiamo il metodo Modifica_Proprietario()
     */
    static function ModificaProfiloProprietario(){
        if($_SERVER['REQUEST_METHOD']=="GET"){

            if(CSession::isLoggedUtente()){
                $view = new VModificaProfiloProprietario();
                $view->mostraModificaProfilo(CSession::getUtente(),"");
            } else {
                //redirect alla form di login
                header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
            }
        }
        else if($_SERVER['REQUEST_METHOD']=="POST"){
            if(CSession::isLoggedUtente()){

                static::Modifica_Proprietario();

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
     * Metodo che gestisce la modifica profilo del proprietario
     */
    static function Modifica_Proprietario()
    {
        $utente=CSession::getUtente();  // recupero l' oggetto proprietario dai dati di sessione
        $username=$utente->getUserName();  //salvo la username iniziale
        $password=$utente->getPass();
        $view = new VModificaProfiloProprietario();
        $dati=$view->recuperaDatiProp();   //salvo i dati inseriti nel form di modifica su un array
        $errore=EProprietario::validaInputModifica($dati,$username,$password); //valido l' input nelle entity
        if($errore){
            $view->mostraModificaProfilo($utente,$errore);
        }
        else {
            $pm = FPersistentManager::getInstance();
            $utente->setUserName($dati['username']);
            $utente->setEmail($dati['email']);
            $utente->setPass($dati['password']);
            $utente->setpIVA($dati['PIVA']);

            $esito = $pm->updateDiUtente($utente);  // aggiorno il database con i nuovi attributi (del proprietario con quell' id)

            if($esito){    // se l' aggiornamento ha avuto successo prende il proprietario aggiornato dal db e lo logga
                CSession::setUtenteLoggato($utente); //aggiorno l'oggetto utente
                header('Location: /NoBarriersApp/homepage/Profilo');  //MOSTRA IL PROFILO AGGIORNATO
            } else {
                $viewerr = new VErrore();
                $viewerr->mostraErrore("Errore in fase di modifica");
            }
        }

    }
    /**
     * Modifica della foto profilo del proprietario
     * Se l' utente non è loggato (sessione scaduta) redirect al login
     * Se la dimensione dell' immagine supera i 5M messaggio di errore
     * Se l' update non ha successo diverso messaggio di errore
     */
    static function ModificaFotoProprietario()
    {
        if (CSession::isLoggedUtente()) {      // è suficiente dimostrare che l'utente sia loggato non serve verificare che si tratti di un visitatore
            $pm = FPersistentManager::getInstance();
            $utente = CSession::getUtente();
            $view = new VProfiloProprietario();
            $fotoobj = $view->recuperaFoto();
            $foto = $fotoobj[0];
            $errore = $fotoobj[1];
            if ($errore== null) {
                $foto->setIdoggetto($utente->getId());
                $esito = $pm->updateFotoProprietario($foto);
                if ($esito) {
                    $ut = $pm->load("EProprietario", $utente->getId());
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
     * Funzione per l' inserimento di una nuova attività da parte dell'utente proprietario(può inserirla solo dal suo profilo),se richiamato tramite get
     * mostra il form di inserimento, se richiamato con il metodo post procede con l' inserimento e imposta la visibilità=0, solo l'amministratore
     * potrà rendere l' attività visibile
     */
    static function NuovaAttivita(){
            if ($_SERVER['REQUEST_METHOD'] == "GET"){
                if(CSession::isLoggedUtente()){
                    $pm = FPersistentManager::getInstance();
                    $view = new VInserimentoAttivita();
                    $citta = $pm->TutteLeCitta();
                    $categorie = $pm->TutteLeCategorie();
                    $view->mostraFormAttivita($citta, $categorie,"");
                }
                else{
                    header('Location: /NoBarriersApp/autenticazione/Login'); // atrimenti redirect al login
                }

        } if ($_SERVER['REQUEST_METHOD'] == "POST"){
                if(CSession::isLoggedUtente()){
                    static::Attivita();
                }
                else{
                    header('Location: /NoBarriersApp/autenticazione/Login'); // atrimenti redirect al login
                }
        }
        else{
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET,POST');
        }

    }

    /**
     * Metodo che gestisce la creazione di una nuova attività
     */
    static function Attivita(){

            if (CSession::isLoggedUtente()) {
                $pm = FPersistentManager::getInstance();
                $view = new VInserimentoAttivita();
                $citta = $pm->TutteLeCitta();
                $categorie = $pm->TutteLeCategorie();
                $annuncio = $view->recuperaDatiAttivita();
                $dati = $annuncio[0];
                $errore = $annuncio[1];
                $errore=$errore."\nTorna indietro per cambiare solo le immagini errate oppure ricompila di seguito il form";
                $proprietario = CSession::getUtente();
                $cittaattivita = $pm->load('ECitta', $dati['citta']);
                $categoriaattivita = $pm->load('ECategoria', $dati['categorie']);
                $attivita = new EAttivita($dati['nome'], $dati['descrizione'], $categoriaattivita, $cittaattivita, $dati['indirizzo'], $proprietario, $dati['data'], $dati['ora']);
                if (count($dati['gallery'])!=0) {   // le immagini rispettano i criteri per il controllo immagine
                $attivita->setImmagini($dati['gallery']);
                $attivita->setHid();
                $id = $pm->store($attivita);
                if ($id) {
                    // reindirizzamento alla home se l' attività è stata salvata nel db con successo
                    header('Location: /NoBarriersApp/homepage/Homepage'); // se la store è avvenuta con successo
                } else {
                    //messaggio di errore inserimento non corretto
                    $viewerr = new VErrore();
                    $viewerr->mostraErrore("Inserimento nuova attività non riuscito");
                }
            }
                else{     // se le immagini non rispettano il formato mostra di nuovo il template complilato con la scritta di errore

                    $view->mostraFormAttivita($citta,$categorie,$errore);
                }

            }else {
                header('Location: /NoBarriersApp/autenticazione/Login'); // atrimenti redirect al login
            }

        }

    /**
     * @param $idproprietario l' id serve per visualizzare una descrizione breve delle attività del proprietario
     * l' id passato come parametro rende l' applicazione vulnerabile a causa di un accesso non consentito
     * ad un' area riservata, per cui viene effettuata una verifica
     */

        static function LemieAttivita($idproprietario)
        {   // non si verifica l' identità dell' utente perchè non compie nessuna operazione
            $utente = CSession::getUtente();
            $idutente = $utente->getId();
            if ($idutente == $idproprietario) {
                $pm = FPersistentManager::getInstance();            // diverso è il caso di modifica o eliminazione
                $attivita = $pm->AttivitaProprietario($idproprietario); // attività che sono state pubblicate
                $attinbreve = array();
                foreach ($attivita as $att) {
                    $galleria = $att->getImmagini();
                    $img = $galleria[0];    //immagine principale
                    $img->setData(base64_encode($img->getData()));
                    $tmp = array(
                        'id' => $att->getId(),
                        'nome' => $att->getNome(),
                        'imgprincipale' => $img,
                        'citta' => $att->getCitta()->getNome(),
                        'categoria' => $att->getCategoria()->getNome()
                    );
                    $attinbreve[] = $tmp;
                }
                if ($attinbreve == null) {
                    $errore = "non è stata ancora pubblicata alcuna attivita";
                } else {
                    $errore = "";
                }

                $view = new VAttivitaPossedute();
                $view->mostraMieAttivita($utente, $attinbreve, $errore);  //$utente per la navbar

            }
            else {
                $view=new VErrore();
                $view->mostraErrore("ACCESSO NON CONSENTITO AD UN'AREA RISERVATA");
            }
        }

    /**
     * @param $idattivita id dell' attività che si vuole eliminare
     * @param $idutente  id dell' utente che è autorizzato ad eliminare quell' attività (il proprietario), preso da 'lemieattivita'
     *Se l' utente è loggato ed è il proprietario allora può procedere con l' eliminazione.Se l' eliminazione non va a buon fine
     * viene lanciato un messaggio di errore altrimenti si fa il refresh della pagina.
     * Se il proprietario cerca di eliminare un'attività che non è stata ancora approvata dall' amministratore riceve un messaggio di errore
     * Se l' utente è loggato ma non è il proprietario di quell' attivita, segue il messaggio di "errore operazione non consentita".
     * Se la sessione è scaduta l'utente viene rediretto al login, se si logga con un' altra username "accesso non consentito ad un' area riservata"
     */

        static function EliminaAttivita($idattivita,$idutente){
            if (CSession::isLoggedUtente()) {
                $proprietario = CSession::getUtente();
                $idproprietario = $proprietario->getId();
                if ($idproprietario == $idutente) {
                    $pm = FPersistentManager::getInstance();
                    $attivita=$pm->load('EAttivita',$idattivita);
                    $vis=$attivita->getVis();
                    if($vis==1) {
                        $esito = $pm->delete('EAttivita', $idattivita);  //si tratta di un valore booleano
                        if ($esito) {
                            static::LemieAttivita($idproprietario);  // aggiorno la pagina
                        } else {
                            $view = new VErrore();
                            $view->mostraErrore("NON è stato possibile eliminare l' attività");
                        }
                    }
                    else{
                        $view=new VErrore();
                        $view->mostraErrore("Operazione non consentita! L'attività sarà visionata dall' amministratore");

                    }

                }
                else{
                    $view=new VErrore();
                    $view->mostraErrore("OPERAZIONE NON CONSENTITA");
                }
            }
            else {
                header('Location: /NoBarriersApp/autenticazione/Login'); // REDIRECT AL LOGIN, se l' utente si logga con un' altra username, messaggio di errore
            }
        }

    /**
     * @param $idattivita che si vuole modificare
     * @param $idproprietario id del proprietario di quell' attività
     * @throws SmartyException
     * Questo metodo consente di modificare descrizione e indirizzo di una attività che già è stata pubblicata.
     * Se la richiesta è di tipo GET mostra il form di modifica
     * Se la richiesta è POST richiama il metodo Modifica_Attivita
     * Se l' utente non è loggato redirect al login
     */

        static function ModificaAttivita($idattivita,$idproprietario){

            $pm=FPersistentManager::getInstance();
            $attivita=$pm->load('EAttivita',$idattivita);


            if($_SERVER['REQUEST_METHOD']=="GET"){

                if(CSession::isLoggedUtente()){                    // se l' utente è loggato
                    $prop=CSession::getUtente();$id=$prop->getId();
                    if ($idproprietario==$id) {                  // se l' utente è effettivamente il proprietario
                        $view = new VModificaAttivita();
                        $view->mostraFormAttivita($attivita);
                    }
                    else{
                        $view=new VErrore();$view->mostraErrore("Operazione non consentita!");
                    }
                } else {
                    //redirect alla form di login
                    header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
                }
            }
            else if($_SERVER['REQUEST_METHOD']=="POST"){
                if(CSession::isLoggedUtente()){
                    $prop=CSession::getUtente();$id=$prop->getId();
                    if ($idproprietario==$id){

                        CProprietario::Modifica_Attivita($idattivita);

                     }
                    else{
                        $view=new VErrore();$view->mostraErrore("Operazione non consentita!");
                    }
                }else {

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
     * Metodo che gestisce la modifica di descrizione e indirizzo di una attività
     */
    static function Modifica_Attivita($idattivita)
    {
        $view = new VModificaAttivita();
        $dati=$view->recuperaDatiAttivita();   //salvo i dati inseriti nel form di modifica su un array
        $att=new EAttivitamodificata($dati['descrizione'],$dati['indirizzo'],$idattivita);
        $pm = FPersistentManager::getInstance();
        $id=$pm->store($att);
        if($id){
            header('Location: /NoBarriersApp/homepage/Profilo');
        }
            else {
                $viewerr = new VErrore();
                $viewerr->mostraErrore("Errore in fase di modifica");
            }
    }

    /**
     * @param $idattivita di cui si vogliono modificare le foto
     * @param $idproprietario per verificare l' identità dell' utente
     * Questo metodo consente di modificare le foto di una attività che già è stata pubblicata.
     * Se la richiesta è di tipo GET mostra il form di modifica
     * Se la richiesta è POST richiama il metodo Modifica_Attivita
     * Se l' utente non è loggato redirect al login
     *
     */
    static function ModificaFotoAttivita($idattivita,$idproprietario){

        if($_SERVER['REQUEST_METHOD']=="GET"){
            if(CSession::isLoggedUtente()){
                $prop=CSession::getUtente();$id=$prop->getId();
                if ($idproprietario==$id) {
                    $errore='';
                    $view = new VModificaAttivita();
                    $view->mostraFormFoto($idattivita,$idproprietario,$errore);
                }
                else{
                    $view=new VErrore();$view->mostraErrore("Operazione non consentita!");
                }
            } else {
                //redirect alla form di login
                header('Location: /NoBarriersApp/autenticazione/Login');// REDIRECT AL LOGIN
            }
        }
        else if($_SERVER['REQUEST_METHOD']=="POST"){
            if(CSession::isLoggedUtente()){
                $prop=CSession::getUtente();$id=$prop->getId();
                if ($id==$idproprietario){
                    CProprietario::Modifica_Foto($idattivita,$idproprietario);

                }
                else{
                    $view=new VErrore();$view->mostraErrore("Operazione non consentita!");
                }
            }else {

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
     * Metodo che gestisce la modifica delle foto di una attività
     */

    static function Modifica_Foto($idattivita,$idproprietario){
        $view=new VModificaAttivita();
        $annuncio=$view->recuperaFoto();
        $pm = FPersistentManager::getInstance();
        $dati = $annuncio[0];
        $errore = $annuncio[1];
        if(empty($errore)) {
           foreach($dati as $d)
            {   foreach($d as $img) {
                $img->setIdoggetto($idattivita);
                $idim = $pm->AggiungiImgModificate($img);
                if (!$idim) {
                    $viewer = new VErrore();
                    $viewer->mostraErrore("Errore nel caricamento");
                }
            }
            }
                 header('Location: /NoBarriersApp/homepage/Profilo');
            }
            else{
                $vista=new VModificaAttivita();
                $vista->MostraFormFoto($idattivita,$idproprietario,$errore);
            }
        }





}









