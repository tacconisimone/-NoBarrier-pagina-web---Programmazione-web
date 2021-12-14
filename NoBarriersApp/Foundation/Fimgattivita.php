<?php


class Fimgattivita extends FDatabase
{
    public function __construct()
    {
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "imgattivita";
        $this->class = "Fimgattivita";
        $this->values = "(:id, :data, :type, :idattivita)";
    }
    /**
     * Questo metodo lega gli attributi di imgattivita che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $img immagini i cui dati devono essere inseriti nel DB
     */

    static function bind($stmt, EImmagine $img)
    {
        $stmt->bindValue(':id', NULL, PDO::PARAM_INT);
        $stmt->bindValue(':data', $img->getData(), PDO::PARAM_LOB);
        $stmt->bindValue(':type', $img->getType(), PDO::PARAM_STR);
        $stmt->bindValue(':idattivita', $img->getIdoggetto(), PDO::PARAM_INT);

    }

    /**Metodo che ricostruisce la galleria di immagini di un' attività
     * @param id attività
     * @return arrayimg array di EImmagine
     **/
    public function loadByIdAttivita($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE idattivita=" . $id . ";";
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
     * Questo metodo crea un oggetto da una riga della tabella imgattivita
     * @param $row array che rappresenta una riga della tabella imgattivita
     * @return un oggetto di tipo EImmagine
     */
    public function getObjectFromRow($row)
    {

        $img = new EImmagine($row['data'], $row['type']);
        $img->setIdoggetto($row['idattivita']);
        $img->setId($row['id']);
        return $img;
    }

    public function UpdateImmagini($img)  //$img=aeeay di EImmagini (modificate)
    {
        $imgprinc = $img[0];  // un oggetto EImmagine, ce ne sono cinque ma l'idoggetto è sempre quello dell' id attivita
        $idimgatt = $imgprinc->getIdoggetto(); // id dell' img attività che si vuole modificare
        $arrayimg = $this->loadByIdAttivita($idimgatt); //le immagini recuperate dal db in base all' id dell' attivita (immagini vecchie)
        $n=count($arrayimg)-1;
        $e1=array();
        for($i=0;$i<=$n;$i++){
            $e2=$this->update($arrayimg[$i]->getId(),"data",$img[$i]->getData());
            $e1[]=$e2;
            $e3=$this->update($arrayimg[$i]->getId(),"type",$img[$i]->getType());
            $e1[]=$e3;
    }
        if (!empty($e1)) {
            return true;
        }
     else
    {
        return false;
  }
}

}