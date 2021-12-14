<?php
/*
 * NON FUNZIONA LA STORE LASTINSERTID=0
 */

require_once 'include.php';
class FValutazione extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "valutazione";
        $this->class = "FValutazione";
        $this->values = "(:id, :commento,:voto, :data, :ora,:idattivita,:idvisitatore)";
    }
    static function bind($stmt, EValutazione $val)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':commento', $val->getText(), PDO::PARAM_STR);
        $stmt->bindValue(':voto', $val->getVote(), PDO::PARAM_INT);
        $stmt->bindValue(':data', $val->getData(), PDO::PARAM_STR);
        $stmt->bindValue(':ora', $val->getOra(), PDO::PARAM_STR);
        $stmt->bindValue(':idattivita', $val->getIdPlace(), PDO::PARAM_INT);
        $stmt->bindValue(':idvisitatore', $val->getIdVisitatore(), PDO::PARAM_INT);

    }

    /**
     * Permette di caricare un commento dal db
     * @param $id del commento
     * @return oggetto di tipo EValutazione
     */
    public function loadById ($id){
        $row = parent::loadbyId($id); //attraverso il metodo della classe padre restituisco la riga
        $arraycomm = $row[0];
        if(($row!=null) && (count($row)>0)){
            $comm = $this->getObjectFromRow($arraycomm);
            return $comm; // oggetto di tipo ECommento
        }
        else return null;
    }
    /**
     * Questo metodo crea un oggetto da una riga della tabella valutazione
     * @param $row array che rappresenta una riga della tabella valutazione
     * @return un oggetto di tipo EValutazione
     */
    public  function getObjectFromRow($row){
        $comm = new EValutazione ($row['commento'], $row['voto'], $row['idattivita'], $row['idvisitatore'],$row['data'],$row['ora']);
        $comm->setId($row['id']);
        return $comm;
    }

    /**Metodo che trova i commenti relativi ad una attività
     * @param id dell' attività
     * @return array di EValutazione
     **/
    public function loadByIdAttivita($id){
        $query = "SELECT * FROM ".$this->table." WHERE idattivita=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arraycomm=array();
            foreach ($rows as $row){
                $comm=$this->getObjectFromRow($row);
                array_push($arraycomm,$comm);     //array pieno di oggetti EValutazione
            }
            return $arraycomm;
        }

        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @return array|null di commenti (gli ultimi 10)
     */

    public function ricercaUltimiCommenti()
    {
        $query = "SELECT * FROM valutazione ORDER BY data DESC, ora DESC LIMIT 10;";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arraycom=array();
            foreach ($rows as $row){
                $com = $this->getObjectFromRow($row);
                array_push($arraycom,$com);
            }
            return $arraycom;
        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }

    }


}