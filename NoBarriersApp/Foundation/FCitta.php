<?php


class FCitta extends FDatabase
{
    public function __construct()
    {
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "citta";
        $this->class = "FCitta";
        $this->values = "(:id, :nome)";
    }
    /**
     * Questo metodo lega gli attributi di citta che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $cit città i cui dati devono essere inseriti nel DB
     */

    static function bind($stmt, ECitta $cit)
    {
        $stmt->bindValue(':id', NULL, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $cit->getNome(), PDO::PARAM_STR);
    }
    /** Load di una citta sul DB in base al suo id.
     * @param $id della citta da caricare
     * @return ECitta recuperata
     */

    public function loadById($id)
    {
        $row = parent::loadById($id);

        if (($row != null) && (count($row) > 0)) {
            $arraycitta = $row[0];
            $citta = $this->getObjectFromRow($arraycitta);
            return $citta;
        } else return null;
    }
    /** Metodo che crea un oggetto ECitta  a partire dalla tupla della tabella citta
     * @param $row array che rappresenta la tupla
     * @return ECategoria $cit
     */
    public function getObjectFromRow($row)
    {
        $cit = new ECitta($row['nome']);
        $cit->setId($row['id']);
        return $cit;
    }

    /**
     * Funzione che verifica se è presente una città con un certo nome
     * @param $nome
     * @return bool|null esito
     */
    public function esisteCit($nome)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE nome= '" . $nome . "';";
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            if (($row != null) && (count($row) > 0)) {
                return true;
            } else return false;

        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }

    /**
     * @param $nome
     * @return ECitta|null
     */

    public function getTown($nome)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE nome= '" . $nome . "';";
        $this->db->beginTransaction();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->commit();
        if (($row != null) && (count($row) > 0)) {
            $rowass = $row[0];
            $cit = $this->getObjectFromRow($rowass);
            return $cit;
        } else return null;
    }

    /**
     * motodo che restituisce tutte le citta nella tabella citta in ordine alfabetico
     * @return array ECitta (tutte quelle presenti nel database inserite dall' amministratore)
     * metodo utilizzato in CHomepage
     */
    public function ListaCitta()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nome ;";  // lista elle citta nel db in ordine alfabetico
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arraycit = array();
            foreach ($rows as $row) {
                $cit = $this->getObjectFromRow($row);
                array_push($arraycit, $cit);
            }
            return $arraycit;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
}