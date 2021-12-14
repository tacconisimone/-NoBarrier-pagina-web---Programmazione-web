<?php


class FAttivitamodificata extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "attivitamodificata";
        $this->class = "FAttivitamodificata";
        $this->values = "(:id, :descrizione, :indirizzo, :idattivita)";
    }
    public static function bind($stmt, EAttivitamodificata $act)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':descrizione', $act->getDescrizione(), PDO::PARAM_STR);
        $stmt->bindValue(':indirizzo', $act->getIndirizzo(), PDO::PARAM_STR);
        $stmt->bindValue('idattivita',$act->getIdAttivita(),PDO::PARAM_INT);

    }

    /** Load di una attività
     * @param $id dell' attività da caricare
     * @return EAttivita recuperata dal db
     */
    public function loadById($id)
    {
        $row = parent::loadById($id);
        if (($row != null) && (count($row) > 0)) {
            $rowass = $row[0];
            $attivita = $this->getObjectFromRow($rowass);
            return $attivita;
        } else return null;
    }
    /** Metodo che crea un oggetto EAttivita
     * @param $row array che rappresenta la tupla
     * @return EAttivita
     */
    public function getObjectFromRow($row){
        $attivita = new EAttivitamodificata($row['descrizione'], $row['indirizzo'], $row['idattivita']);
        $attivita->setId($row['id']);
        return $attivita;
    }

    public function ListaModificate()
    {
        $query = "SELECT * FROM " . $this->table . ";";  // lista delle attivita modificate
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayatt = array();
            foreach ($rows as $row) {
                $att = $this->getObjectFromRow($row);
                array_push($arrayatt, $att);
            }
            return $arrayatt;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @param $id di EAttivita
     * @return array|null di oggetti EAttivitamodificata
     */
    public function AttModificataByIdAttivita($id){
        $query = "SELECT * FROM ".$this->table." WHERE idattivita=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            if (($row != null) && (count($row) > 0)) {
                $rowass = $row[0];
                $attivita = $this->getObjectFromRow($rowass);
                return $attivita;
            }
            else {
                return null;
            }
        }

        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
    public function deleteAttModifiedByIdAtt($idattivita) {
        $query = "DELETE FROM ".$this->table . " WHERE idattivita =".$idattivita.";";
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



}