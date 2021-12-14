<?php

require_once 'include.php';
class EVisitatore extends EUtente
{    /** cognome dell'utente visitatore */
     private $cognome;
     /** nome del visitatore */
     private $nome;
    /** costruttore */
    public function __construct($us,$pass,$name,$em,$surname){
        $this->username=$us;
        $this->password=$pass;
        $this->email=$em;
        $this->nome=$name;
        $this->cognome=$surname;
    }
    /**
     * @param string $name
     */
    public function setNome($name){
        $this->nome=$name;
    }
    /**
     * @return string nome
     */
    public function getNome():string{
        return $this->nome;
    }
    /**
     * @param string $surname
     */
    public function setCognome($surname){
        $this->cognome=$surname;
    }
    /**
     * @return string cognome
     */
    public function getCognome():string{
        return $this->cognome;
    }
    /**
     * Stampa le informazioni riguardati il visitatore
     */
   public function __toString():string {
        $st="Id: ".$this->getId()." Nome: ".$this->getNome()." Cognome: ".$this->getCognome()." UserName: ".$this->getUserName()." Email: ".$this->getEmail()."Immagine:".$this->getImmagine()."\n";
        return $st;
    }
/******************************************************METODI DI VALIDAZIONE*************************************************************************/

    /**
     * Funzione che verifica se il nome inserito è conforme
     * il nome può contenere solo lettere maiuscole e minuscole, minimo 3 e massimo 15
     * @return mixed esito
     */
    static function validaNome($name){
        $accettato = preg_match('/^[A-Za-z]{3,15}$/', $name);
        if($accettato){
            return true;
        } else { return false;}
    }

    /**
     * Funzione che verifica se il cognome inserito è conforme
     * il cognome può contenere solo lettere maiuscole e minuscole, minimo 3 e massimo 15
     * @return mixed esito
     */
    static function validaCognome($surname){
        $accettato = preg_match('/^[A-Za-z]{3,15}$/', $surname);
        if($accettato){
            return true;
        } else { return false;}
    }
    /**
     * Validazione input registrazione
     * @param array di dati del visitatore
     * @return string che mostra l'errore presentato, stringa vuota se non ci sono errori
     */
    static function validaInput($dati){        // questo metodo viene utilizzato nel control
        $errore="";
        if(! static::validaUsername($dati['username'])){
            $errore = $errore."Username già presente.\n";
        }
        if(! static::validaPassword($dati['password'])){
            $errore = $errore."Password non valida.\n";
        }
        if(! static::validaMail($dati['email'])){
            $errore = $errore."La mail non è conforme.\n";
        }
        if(! static::validaNome($dati['nome'])){
            $errore = $errore."Il nome non è valido.\n";
        }
        if(! static::validaCognome($dati['cognome'])){
            $errore = $errore."Il cognome non è valido.\n";
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
        if(! static::validaNome($dati['nome'])){
            $errore = $errore."Il nome non è valido.\n";
        }
        if(! static::validaCognome($dati['cognome'])){
            $errore = $errore."Il cognome non è valido.\n";
        }
        return $errore;
    }


}