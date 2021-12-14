<?php


class FCategoria extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "categoria";
        $this->class = "FCategoria";
        $this->values = "(:id, :nome)";
    }
    /**
     * Questo metodo lega gli attributi di categoria che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $cat categoria i cui dati devono essere inseriti nel DB
     */
    static function bind($stmt, ECategoria $cat)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $cat->getNome(), PDO::PARAM_STR);
    }
    /** Load di una categoria sul DB in base al suo id.
     * @param $id della categoria da caricare
     * @return ECategoria recuperata
     */
    public function loadById($id){
        $row = parent::loadById($id);

        if(($row!=null) && (count($row)>0)){
            $arrcategoria = $row[0];
            $categoria = $this->getObjectFromRow($arrcategoria);
            return $categoria;
        }
        else return null;
    }
    /** Metodo che crea un oggetto ECategoria partire dalla tupla della tabella categoria
     * @param $row array che rappresenta la tupla
     * @return ECategoria
     */
    public function getObjectFromRow($row){
        $cat = new ECategoria($row['nome']);
        $cat->setId($row['id']);
        return $cat;
    }

    /**
     * Funzione che verifica se è presente una categoria con un certo nome
     * @param $nome
     * @return bool|null esito
     */
    public function esisteCat($nome){
        $query = "SELECT * FROM ".$this->table." WHERE nome= '".$nome."';";
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            if(($row != null) && (count($row)>0)){
                return true;
            }
            else return false;

        }
        catch (PDOException $e)
        {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @param $nome
     * @return ECategoria|null
     */
    public function getCategory($nome){
        $query = "SELECT * FROM " . $this->table . " WHERE nome= '" . $nome . "';";
        $this->db->beginTransaction();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->commit();
        if (($row != null) && (count($row) > 0)) {
            $rowass = $row[0];
            $cat = $this->getObjectFromRow($rowass);
            return $cat;
        } else return null;
    }
    /**
     * funzione che prende tutte le categorie nella tabella categorie in ordine alfabetico
     */
    public function ListaCategorie(){
        $query = "SELECT * FROM " . $this->table . " ORDER BY nome ;";  // lista elle categorie nel db in ordine alfabetico
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arraycat = array();
            foreach ($rows as $row) {
                $cat = $this->getObjectFromRow($row);
                array_push($arraycat, $cat);
            }
            return $arraycat;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }



}