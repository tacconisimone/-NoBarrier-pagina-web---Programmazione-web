<?php


class Fimgproprietario extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "imgproprietario";
        $this->class = "Fimgproprietario";
        $this->values = "(:id, :data, :type, :idproprietario)";
    }
    /**
     * Questo metodo lega gli attributi dell'immagine di un proprietario che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $img EImmagine i cui dati devono essere inseriti nel DB
     */
    static function bind($stmt, EImmagine $img)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':data', $img->getData(), PDO::PARAM_LOB);
        $stmt->bindValue(':type', $img->getType(), PDO::PARAM_STR);
        $stmt->bindValue(':idproprietario', $img->getIdoggetto(), PDO::PARAM_INT);

    }

    /**
     * Questo metodo serve per creare un oggetto della classe EImmagine dato l' id dell' utente (viene preso dalla tabella imgutente)
     * @param $idutente
     * @return EImmagine|null
     */
    public function loadByIdUtente($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE idproprietario=" . $id . ";";    //seleziona tutti i campi della tabella in base all' id utente
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);  //array contenente i campi della tupla indicizzati es [0]=>Array() per la tupla1
            $this->db->commit();

            if (($row != null) && (count($row) > 0)) {
                $arrimg = $row[0];
                $img = $this->getObjectFromRow($arrimg);      //richiama il metodo per creare l' oggetto
                return $img; // oggetto di tipo EImmagine
            } else {
                return null;
            }

        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione! errore: " . $e->getMessage();
            return null;
        }


    }
    /**
     * Questo metodo crea un oggetto da una riga della tabella imgutente
     * @param $row array che rappresenta una riga della tabella ingutente
     * @return un oggetto di tipo EImmagine
     */
    public function getObjectFromRow($row){

        $img = new EImmagine($row['data'], $row['type']);
        $img->setIdoggetto($row['idproprietario']);
        $img->setId($row['id']);
        return $img;
    }

    /**
     * Aggiornamento della foto profilo del proprietario
     * @param $foto
     * @return bool
     */
    public function updateFoto($foto){    // per paramentro passo l' oggetto foto che si vuole sostituire a alla foto attuale
        $idut=$foto->getIdoggetto();   // recupero l' id del proprietario dalla foto
        $imm = $this->loadByIdUtente($idut);  // recupero l' img profilo proprietario
        $idimm = $imm->getId();    // prendo l' id dell' immagine
        $er=$this->update($idimm, "data", $foto->getData());  // aggiorno quell' immagine con la nuova(passata per parametro)
        $esito = $this->update($idimm, "type", $foto->getType());  // aggiorno il tipo dell' immagine
        if($er && $esito){
            return true;            // se i due aggiornamenti hanno avuto esito positivo
        } else {
            return false; //altrimenti
        }
    }

}