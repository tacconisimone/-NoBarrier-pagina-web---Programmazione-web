<?php

require_once 'include.php';
class EProprietario extends EUtente
{   /** partita iva */
    private $pIVA;
    /** costruttore */
    public function __construct($us,$pass,$em,$p){
        $this->username=$us;
        $this->password=$pass;
        $this->email=$em;
        $this->pIVA=$p;
    }
    /*
     * @return int pIVA
     */
    public function getpIVA(){
        return $this->pIVA;
    }
    /*
     * @param int $p
     */
    public function setpIVA($p){
        $this->pIVA=$p;
    }
public function __toString():string
{
    $st="Id: ".$this->getId()." UserName: ".$this->getUserName()." Email: ".$this->getEmail()."immagine:".$this->getImmagine()." pIVA: ".$this->getpIVA()."\n";
    return $st;

}
/***********************************************METODI DI VALIDAZIONE****************************************************************************/
    /**
     * Funzione che verifica se la mail inserita è conforme
     * @return mixed esito
     */
    static function validaPIVA($pi){
        $accettato = preg_match('/^[0-9]{11}$/', $pi);
        if($accettato){
            return true;
        } else { return false;}
    }
    /**
     * Validazione input registrazione
     * @param array di dati del visitatore
     * @return string che mostra l'errore presentato, stringa vuota se non ci sono errori
     */
    static function validaInput($dati){
        $errore="";
        if(! static::validaUsername($dati['username'])){
            $errore = $errore."Username già presente.\n";
        }
        if(! static::validaPassword($dati['password'])){
            $errore = $errore."Le password non coincidono.\n";
        }
        if(! static::validaMail($dati['email'])){
            $errore = $errore."La mail non è conforme.\n";
        }
        if(! static::validaPIVA($dati['PIVA'])){
            $errore=$errore."La partita iva non è valida.\n";
        }
        return $errore;
    }
    /**
     * Validazione input modifica profilo
     * @return string che mostra l'errore presentato, stringa vuota se non ci sono errori
     */
    static function validaInputModifica($dati,$username,$password){
        $errore="";
        if(! static::validaUsernameModificata($dati['username'],$username)){    //(user. modificata,user. iniziale)
            $errore = $errore."Username già presente.\n";
        }
        if(! static::validaPasswordModificata($dati['password'],$password)){
            $errore = $errore."Password non valida!.\n";
        }
        if(! static::validaMail($dati['email'])){
            $errore = $errore."La mail non è conforme.\n";
        }
        if(! static::validaPIVA($dati['PIVA'])){
            $errore=$errore."La partita iva non è valida.\n";
        }

        return $errore;
    }





}