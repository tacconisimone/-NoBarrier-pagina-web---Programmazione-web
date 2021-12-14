<?php
/**
 * inclusione di tutte le classi del package Entity
 */
require_once 'Entity/EUtente.php';
require_once 'Entity/EAttivita.php';
require_once 'Entity/ECategoria.php';
require_once 'Entity/ECitta.php';
require_once 'Entity/EImmagine.php';
require_once 'Entity/EProprietario.php';
require_once 'Entity/EValutazione.php';
require_once 'Entity/EVisitatore.php';
require_once 'Entity/EAttivitamodificata.php';   //
/**
 * inclusione delle classi del package Foundation
 */
require_once 'Foundation/FDatabase.php';
require_once 'Foundation/FAttivita.php';
require_once 'Foundation/FAttivitamodificata.php';    //
require_once 'Foundation/FCategoria.php';
require_once 'Foundation/FCitta.php';
require_once 'Foundation/Fimgattivita.php';
require_once 'Foundation/Fimgattmodificate.php';    //
require_once 'Foundation/FVisitatore.php';
require_once 'Foundation/Fimgvisitatore.php';
require_once 'Foundation/FProprietario.php';
require_once 'Foundation/Fimgproprietario.php';
require_once 'Foundation/FValutazione.php';
require_once 'Foundation/FPersistentManager.php';



require_once 'View/VLogin.php';
require_once 'View/VRegistrazioneVisitatore.php';
require_once 'View/VRegistrazioneProprietario.php';
require_once 'View/VModificaProfiloVisitatore.php';
require_once 'View/VModificaProfiloProprietario.php';
require_once 'View/VProfiloVisitatore.php';
require_once 'View/VProfiloProprietario.php';
require_once 'View/VInserimentoAttivita.php';
require_once 'View/VErrore.php';
require_once 'View/VDettaglioAttivita.php';
require_once 'View/VValutazione.php';
require_once 'View/VHomepage.php';
require_once 'View/VCategorie.php';
require_once 'View/VAnteprimaAttivita.php';
require_once 'View/VListaAttivita.php';
require_once 'View/VAttivitaPossedute.php';
require_once 'View/VNuovaCategoria.php';
require_once 'View/VNuovaCitta.php';
require_once 'View/VMessaggio.php';      //
require_once 'View/VModificaAttivita.php';   //
require_once 'View/VAttivitaModificate.php';  //


require_once 'Controller/CSession.php';
require_once 'Controller/CAutenticazione.php';
require_once 'Controller/CVisitatore.php';
require_once 'Controller/CProprietario.php';
require_once 'Controller/CAttivita.php';
require_once 'Controller/CAmministratore.php';
require_once 'Controller/CHomepage.php';
require_once 'Controller/CFrontController.php';

