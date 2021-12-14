<?php


class Fimgattmodificate extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "imgattmodificate";
        $this->class = "Fimgattmodificate";
        $this->values = "(:id, :data, :type, :idattivita)";
    }
    static function bind($stmt, EImmagine $img)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':data', $img->getData(), PDO::PARAM_LOB);
        $stmt->bindValue(':type', $img->getType(), PDO::PARAM_STR);
        $stmt->bindValue(':idattivita', $img->getIdoggetto(), PDO::PARAM_INT);

    }

    /**Metodo che ricostruisce la galleria di immagini di un' attività
     * @param id attività
     * @return arrayimg array di EImmagine
     **/
    public function loadByIdAttivita($id){
        $query="SELECT * FROM ".$this->table." WHERE idattivita=".$id.";";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayimg = array();
            foreach ($rows as $row){
                $img = $this->getObjectFromRow($row);
                array_push($arrayimg, $img);
            }
            return $arrayimg;
        }

        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
    /**
     * Questo metodo crea un oggetto da una riga della tabella imgattivita
     * @param $row array che rappresenta una riga della tabella imgattmodificate
     * @return un oggetto di tipo EImmagine
     */
    public function getObjectFromRow($row){

        $img = new EImmagine($row['data'], $row['type']);
        $img->setIdoggetto($row['idattivita']);
        $img->setId($row['id']);
        return $img;
    }
    public function ListaModificate()
    {
        $query = "SELECT * FROM " . $this->table . ";";  // lista delle immagini modificate
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayimg = array();
            foreach ($rows as $row) {
                $img = $this->getObjectFromRow($row);
                array_push($arrayimg, $img);
            }
            return $arrayimg;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
/**
 * Sia in caso di approvazione delle modifiche apportate ad un'attività che in caso di rifiuto, le modifiche
 *richieste per l' attività vengono rimosse dopo che l' amministratore ne ha preso visione
 */
    public function deleteGalleryByIdAtt($idattivita) {
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