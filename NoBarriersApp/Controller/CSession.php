<?php


class CSession
{
    /**
     * Metodo che verifica se l'utente è loggato, ovvero se la componente 'visitatore' di $_SESSION, è settata
     * @return bool
     */
    static function isLoggedUtente(){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }

        if(isset($_SESSION['utente'])){
            return true;
        } else {
            return false;
        }
    }


    /**
     * Metodo che verifica se l'amministratore è loggato, ovvero se la componente 'amministratore' di $_SESSION, è settata
     * @return bool
     */
    static function isLoggedAdmin(){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }

        if(isset($_SESSION['amministratore'])){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo che recupera l'utente loggato dai dati di sessione
     * @return EUtente loggato
     */
    static function getUtente(){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        $u = $_SESSION['utente']; //stringa
        $utente = unserialize($u);
        return $utente;
    }

    /**
     * Metodo che salva nei dati di sessione l' utente (quando il login ha successo)
     * @param $visit da salvare in $_SESSION
     */
    static function setUtenteLoggato($ut){    // si tratta di un oggetto
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        $u = serialize($ut);
        $_SESSION['utente'] = $u;
    }

    static function logout(){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        session_unset();
        session_destroy();
    }

    /**
     * Metodo che salva nei dati di sessione che l'amministratore è loggato (quando il login amministratore ha successo
     */
    static function setAdminLoggato(){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION['amministratore'] = true;
    }

    static function setPath($path){
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION['path'] = $path;
    }

    static function getPath(){
        if(session_status()==PHP_SESSION_NONE){
           session_start();
        }
        $path = $_SESSION['path']; //stringa
        return $path;
    }

    static function removePath() {
        if(session_status()==PHP_SESSION_NONE){
            session_start();
        }
        unset($_SESSION['path']);
    }

}