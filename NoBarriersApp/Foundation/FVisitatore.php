<?php

require_once 'include.php';
class FVisitatore extends FDatabase
{
    public function __construct(){
        parent::__construct();
        $this->table= 'visitatore';
        $this->values='(:id,:username,:password,:email,:stato,:cognome,:nome)';
        $this->class='FVisitatore';
    }


    /**
     * Questo metodo lega gli attributi dell'utente che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $user utente i cui dati devono essere inseriti nel DB
     */
    public static function bind($stmt, EVisitatore $visit){
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':username', $visit->getUserName(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $visit->getPass(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $visit->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':stato', $visit->getStato(), PDO::PARAM_BOOL);
        $stmt->bindValue(':cognome', $visit->getCognome(), PDO::PARAM_STR);
        $stmt->bindValue(':nome', $visit->getNome(), PDO::PARAM_STR);

    }

    /**
     * Metodo che esegue la load dell'utente in base all'id
     * @param int $id dell'user
     * @return EUtente recuperato
     */
    public function loadById($id){
        $row = parent::loadById($id); //restituisce la tupla recuperta dal db, che non è completa, l' immagine dell' utente è stata salvata in imgutente
        if(($row != null) && (count($row)>0)){
            $rowass=$row[0];
            $rowcompleta = $this->buildRow($rowass);
            $ut = $this->getObjectFromRow($rowcompleta);
            return $ut;
        }
        else return null;
    }

    /**
     * @param $row array contenente gli attributi di un utente
     * @return mixed
     */
    private function buildRow($row){
        //caricamento foto profilo utente
        $fimg = new Fimgvisitatore();
        $img = $fimg->loadByIdUtente($row['id']); //prende la tupla dell' immagine relativa all' utente e la trasforma in un oggetto
        $row['immagine'] = $img; //nel campo 'immagine dell' array row salvo l' oggetto'

        return $row;
    }
    public function getObjectFromRow($row)
    {
        $visit=new EVisitatore($row['username'],$row['password'],$row['nome'],$row['email'],$row['cognome']);
        $visit->setId($row['id']);
        $visit->setStato($row['stato']);
        $visit->setImmagine($row['immagine']);  //non fa parte della tabella utente
        return $visit;
    }
    public function store($obj){
        $id = parent::store($obj);
        if($id){

            //salvataggio immagine di default per l'utente
            $img = file_get_contents('./images/imgprofilo.png');
            $imgobj = new EImmagine($img,'image/png');
            $imgobj->setIdoggetto($id);
            $fimut = new Fimgvisitatore();
            $fimut->store($imgobj);
            return $id;

        }
        else return false;
    }

    /**
     * Funzione che verifica se è presente un utente con un certo username
     * @param $username
     * @return bool|null esito
     */
    public function esisteUsername($username){
        $query = "SELECT * FROM ".$this->table." WHERE username= '".$username."';";
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
     * Funzione che aggiorna un utente
     * @param $utente
     * @return bool|null esito
     */

    public function updateUtente($utente){

        $e1=$this->update($utente->getId(),'nome',$utente->getNome());
        $e2=$this->update($utente->getId(),'cognome',$utente->getCognome());
        $e3=$this->update($utente->getId(),'username',$utente->getUsername());
        $e4=$this->update($utente->getId(),'password',$utente->getPass());
        $e5=$this->update($utente->getId(),'email',$utente->getEmail());

        if($e1 && $e2 && $e3 && $e4 && $e5){
            return true;
        } else {
            return false;
        }
    }
    /**
     * Funzione che recupera un visitatore con un certo username
     * @param $username
     * @return EVisitatore $ut
     */
    public function getVisitatore($username)
    {
        $query = "SELECT * FROM ".$this->table." WHERE username= '".$username."';";
        $this->db->beginTransaction();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->db->commit();
        if(($row != null) && (count($row)>0)){
            $rowass=$row[0];
            $rowcompleta = $this->buildRow($rowass);
            $ut = $this->getObjectFromRow($rowcompleta);
            return $ut;
        }
        else return null;

    }


}