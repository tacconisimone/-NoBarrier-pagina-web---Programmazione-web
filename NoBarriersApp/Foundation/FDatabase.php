<?php
require_once 'include.php';
class FDatabase
{
    /** oggetto PDO per la connessione al DBMS */
    protected $db;

    /** nome della tabella */
    protected $table;

    /** nome della classe */
    protected $class;

    /** attributi della tabella */
    protected $values;

    public function __construct()
    {
        global $host, $database, $username, $password;
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        } catch (PDOException $e)                    //qualsiasi classe che richiama uno di questi metodi realizza una connessione
            // con il database, per default la connessione viene chiusa ogni volta che si esegue uno
            //script, non è quindi necessario insrire un distruttore, basta annullare tutte le modifiche in caso di eccezione
            // questo tipo di applicazione non viene pensata con un grande overhead, per cui non è persistente
        {
            echo "Attenzione errore: " . $e->getMessage();
            die;
        }

    }

    /**
     * Load di un oggetto dal database noto il suo id
     * @param $id dell' oggetto da recuperare
     * @return tupla recuperata
     */

    public function loadById($id){
        $query = "SELECT * FROM ".$this->table." WHERE id=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            return $row;     //si tratta di un array
        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
    /** metodo che viene richiamato dalle altre classi per poter inserire un oggetto  sul DB
     * @param $obj oggetto da salvare
     * @return $id dell'oggetto caricato
     */
    public function store($obj) {

        $query = "INSERT INTO ".$this->table." VALUES ".$this->values.";";
        try {

            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $this->class::bind($stmt,$obj);
            $stmt->execute();
               $id = $this->db->lastInsertId();
               $this->db->commit();
            return $id;


        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return false;
        }
    }

    /**
     * @param $id della risorsa che si vuole eliminare
     * @return bool
     */

    public function delete($id) {
        $query = "DELETE FROM ".$this->table . " WHERE id=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $this->db->commit();
            return true;
        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return false;
        }
    }
    /**
     * Update di una npla del database
     * @param $id dell' oggetto da aggiornare, $attr della tabella, $val nuovo valore
     * @return boolean
     */

    public function update($id, $attr, $val) {
        $query = "UPDATE ".$this->table. " SET ".$attr." = '".$val."' WHERE id=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $this->db->commit();
            return true;
        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return false;
        }
    }
    /**
     * Verifica esistenza di una tupla nel database
     * @param $id id della tupla
     * @return boolean
     */
    public function exist($id) {
        $query = "SELECT * FROM ".$this->table." WHERE id=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows)>0) return true;
            else return false;
            $this->db->commit();
        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }


}