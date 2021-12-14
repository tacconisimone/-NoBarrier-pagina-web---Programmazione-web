<?php
/** La classe EUtente è stata definita abstract perchè non se ne possono fre istanze. Questa
 *classe generalizza gli attori dei due scenari dell'applicazione: Visitatore e Proprietario.
 * Gli attributi sono direttamente accessibili per tutte le sottoclassi, i metodi vengono ereditati,il ToString va
 * necessariamente impleme
 */
require_once 'include.php';
abstract class EUtente
{
    /** id utente */
    protected $id;
    /** username dell' utente */
    protected $username;
    /** password dell' account */
    protected $password;
    /** email personale */
    protected $email;
    /** immagine del profilo */
    protected $immagine;
    /** stato utente */
    protected $stato=true;


    /**
     * @return int id utente
     */
    public function getId():int{
        return $this->id;
    }
    /**
     * @return string username
     */
    public function getUserName():string {
        return $this->username;
    }
    /**
     * @return string password
     */
    public function getPass():string {
        return $this->password;
    }
    /**
     * @return string email
     */
    public function getEmail():string {
        return $this->email;
    }
    /**
     * return bool stato
     */
    public function getStato():bool
    {
        return $this->stato;
    }

    /**
     * @param int $id utente
     */
    public function setId($id){
        $this->id=$id;
    }
    /**
     * @param string $un username
     */
    public function setUserName($un){
        $this->username=$un;
    }
    /**
     * @param string $pass password
     */
    public function setPass($pass){
        $this->password=$pass;
    }
    /**
     * @param string $em email
     */
    public function setEmail($em){
        $this->email=$em;
    }
    public function setStato($s)
    {
        $this->stato=$s;
    }

    /**
     * @return mixed
     */
    public function getImmagine(): EImmagine
    {
        return clone $this->immagine;           //copia esatta dell' immagine
    }

    /**
     * @param mixed $immagine
     */
    public function setImmagine($immagine): void
    {
        $this->immagine = $immagine;
    }

 /*******************************************METODI DI VALIDAZIONE*********************************************************************/

    /**
     * Funzione che verifica se la mail inserita è conforme
     * @return mixed esito
     */
    static function validaMail($mail){
        $accettato = preg_match('/^[A-z0-9\.\+_-]+@[A-z0-9\._-]+\.[A-z]{2,6}$/', $mail);
        if($accettato){
            return true;
        } else { return false;}
    }

    /**
     * @param $user sting username
     * @return bool
     */
    static function validaUsername($user){                    //si verifica solo che la username sia unica, ovvero che non sia stata già assegnata ad altri utenti
        $pm = FPersistentManager::getInstance();            // un proprietario non può avere la stessa username di un visitatore e viceversa
        $esito = $pm->esisteUsername($user);               //l' unica validazione sulla username riguarda l' unicità, non caratteri particolari
        if($esito){
            return false;
        } else { return true;}
    }

    /**
     * @param $user string username modificata
     * @param $firstusername string username iniziale
     * @return bool
     */
     static function validaUsernameModificata($user,$firstusername){   // questo metodo viene utilizzato dal Controller per verificare se la username modificata coincide con la vecchia o è conforme
         if($user==$firstusername){    // verifico se la nuova username coincide con quella precedente
             $valido = true;
         } else {
             $valido=static::validaUsername($user); // verifico se nel db è già presente la username che si vuole assegnare
             }

         if($valido){
             return true;
         } else { return false;}

     }
    static function validaPasswordModificata($user,$firstpass){   // questo metodo viene utilizzato dal Controller per verificare se la username modificata coincide con la vecchia o è conforme
        if($user==$firstpass){    // verifico se la nuova username coincide con quella precedente
            $valido = true;
        } else {
            $valido=static::validaPassword($user); // verifico se nel db è già presente la username che si vuole assegnare
        }

        if($valido){
            return true;
        } else { return false;}

    }

    /**
     * Metodo che verifica se l'email dell'istanza sia corretta. Una password corretta
     * deve contenere almeno un numero, almeno una lettera minuscola e almeno una lettera maiuscola
     * @return bool true se la password e' corretta, false altrimenti
     */
    static function validaPassword($pass) : bool
    {
        if($pass && preg_match('/^[[:alnum:]]{6,100}$/', $pass)) // solo numeri-lettere da 6 a 20
        {
            return true;
        }
        else
            return false;
    }

    /**
     * Metodo che effettua l'hash della password
     */
    public function hashPassword ()
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }







}