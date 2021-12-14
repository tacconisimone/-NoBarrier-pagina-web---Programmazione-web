<?php

class FAttivita extends FDatabase
{
    public function __construct(){
        parent::__construct(); //estende la superclasse FDatabase
        $this->table = "attivita";
        $this->class = "FAttivita";
        $this->values = "(:id, :nome, :descrizione, :indirizzo, :data, :ora, :visibility, :idcategoria, :idcitta, :idproprietario)";
    }
    /**
     * Questo metodo lega gli attributi dell'attività che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $act EAttivita i cui dati devono essere inseriti nel DB
     */
    public static function bind($stmt, EAttivita $act)
    {
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':nome',$act->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':descrizione', $act->getDescrizione(), PDO::PARAM_STR);
        $stmt->bindValue(':indirizzo', $act->getIndirizzo(), PDO::PARAM_STR);
        $stmt->bindValue(':data', $act->getData(), PDO::PARAM_STR);
        $stmt->bindValue(':ora', $act->getOra(), PDO::PARAM_STR);
        $stmt->bindValue(':visibility', $act->getVis(), PDO::PARAM_INT);
        $stmt->bindValue(':idcategoria', $act->getCategoria()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':idcitta', $act->getCitta()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':idproprietario', $act->getProprietario()->getId(), PDO::PARAM_INT);

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
            $rowcompleta = $this->buildRow($rowass);
            $attivita = $this->getObjectFromRow($rowcompleta);
            return $attivita;
        } else return null;
    }
        /** Metodo che costruisce una row completa per l' attività, pronta per essere passata al metodo getObjectFromRow
         * @param $row presa dal database
         * @return $row con commenti e immagini
         */
        private function buildRow($row){

            //caricamento commenti
            $fcomm = new FValutazione();
            $arrayval = $fcomm->loadByIdAttivita($row['id']);  //restituisce un array pieno di oggetti EValutazione
            $row['valutazioni'] = $arrayval;
            $fimmagini = new Fimgattivita();
            $gallery = $fimmagini->loadByIdAttivita($row['id']);
            $row['gallery'] = $gallery;
            return $row;
        }

    /** Metodo che crea un oggetto EAttivita
     * @param $row array che rappresenta la tupla
     * @return EAttivita
     */
    public function getObjectFromRow($row){
        $fc = new FCategoria();
        $cat = $fc->loadById($row['idcategoria']);
        $fcit=new FCitta();
        $cit=$fcit->loadById($row['idcitta']);
        $fprop=new FProprietario();
        $prop=$fprop->loadById($row['idproprietario']);
        $attivita = new EAttivita($row['nome'], $row['descrizione'],$cat, $cit, $row['indirizzo'],$prop,$row['data'],$row['ora']);
        $attivita->setId($row['id']);
       // $attivita->setImgesposta($row['imgesposta']);
        $attivita->setImmagini($row['gallery']);
        $attivita->setValutazioni($row['valutazioni']);
        $attivita->setVis($row['visibility']);

        return $attivita;
    }

    /** Store di un oggetto EAttivita sul DB
     * @param EAttivita $attivita da salvare
     * @return void
     */
    public function store($attivita)
    {
        $id = parent::store($attivita);
         if($id){
        $gallery = $attivita->getImmagini();
        $fgallery = new Fimgattivita();
        foreach ($gallery as $g) {
            $g->setIdoggetto($id);
            $fgallery->store($g);
        }
        return $id;
         }

         else return false;
    }
    /** Metodo che cerca un'attività tramite l'id di una città e di una categoria
     * @param ECitta $idcitta
     * @param ECategoria $idcategoria
     * @return array $arrayatt
     */

    public function CercaAttPerCittaEcategoria($idcitta,$idcategoria)
    {
       $query = "SELECT * FROM ".$this->table." WHERE idcitta=".$idcitta." AND idcategoria=".$idcategoria." AND visibility=1 ;";  //attenzione agli spazi

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayatt = array();
            if (($rows != null) && (count($rows) > 0)) {
                foreach ($rows as $row) {
                    $rowcompleta = $this->buildRow($row);
                    $att = $this->getObjectFromRow($rowcompleta);
                    array_push($arrayatt, $att);
                }

                return $arrayatt;
            } else {
                return null;
            }

        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "Attenzione, errore: " . $e->getMessage();
            return null;
        }
    }
    /** Metodo che restituisce le ultime attività
     * @return array $arrayatt
     */

    public function UltimeAttivita()
    {
        $query = "SELECT * FROM attivita WHERE visibility=0 ;";   // non ho potuto mettere from $this->table ed ho dovuto togliere order by data desc
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayatt = array();
            if (($rows != null) && (count($rows) > 0)) {
                foreach ($rows as $row) {
                    $rowcompleta = $this->buildRow($row);
                    $att = $this->getObjectFromRow($rowcompleta);
                    $att->setHid();                     // è solo una traduzione della query effettuata al database, non potevo metterlo in getobgectfromrow
                    array_push($arrayatt, $att);
                }
                return $arrayatt;
            } else {
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
    /** Metodo che restituisce la lista delle attività di un certo proprietario tramite il suo id
     * @return array $arrayatt
     */

    public function LeMieAttivita($id)   //id del proprietario
    {
        $query ="SELECT * FROM ".$this->table." WHERE idproprietario=".$id." AND visibility=1 ;" ;   // non ho potuto mettere from $this->table ed ho dovuto togliere order by data desc
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->db->commit();
            $arrayatt = array();
            if (($rows != null) && (count($rows) > 0)) {
                foreach ($rows as $row) {
                    $rowcompleta = $this->buildRow($row);
                    $att = $this->getObjectFromRow($rowcompleta);
                    array_push($arrayatt, $att);
                }
                return $arrayatt;
            } else {
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
    /** Metodo che aggiorna un'attività
     * @param EAttivita $att
     * @return bool esito
     */

    public function UpdateAttivita($att){  // $att è un oggetto EAttivitamodificata
        $e1=$this->update($att->getIdAttivita(),'descrizione',$att->getDescrizione());
        $e2=$this->update($att->getIdAttivita(),'indirizzo',$att->getIndirizzo());
        if($e1 && $e2 ){
                return true;

        } else {
            return false;
        }
    }



}


