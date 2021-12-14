<?php


class FProprietario extends FDatabase
{
    public function __construct(){
        parent::__construct();
        $this->table= 'proprietario';
        $this->values='(:id,:username,:password,:email,:stato,:pIVA)';
        $this->class='FProprietario';
    }


    /**
     * Questo metodo lega gli attributi dell'utente che si vogliono inserire con i parametri della INSERT
     * @param $stmt
     * @param $user utente i cui dati devono essere inseriti nel DB
     */
    public static function bind($stmt, EProprietario $pro){
        $stmt->bindValue(':id',NULL, PDO::PARAM_INT);
        $stmt->bindValue(':username', $pro->getUserName(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $pro->getPass(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $pro->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':stato', $pro->getStato(), PDO::PARAM_BOOL);
        $stmt->bindValue(':pIVA',$pro->getpIVA(),PDO::PARAM_STR);

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
        $fimg = new Fimgproprietario();
        $img = $fimg->loadByIdUtente($row['id']); //prende la tupla dell' immagine relativa all' utente e la trasforma in un oggetto
        $row['immagine'] = $img; //nel campo 'immagine dell' array row salvo l' oggetto'

        return $row;
    }
    public function getObjectFromRow($row)
    {
        $visit=new EProprietario($row['username'],$row['password'],$row['email'],$row['pIVA']);
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
            $fimut = new Fimgproprietario();
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
     * @param $utente EProprietario
     * @return bool
     */

    public function updateUtente($utente){

        $e1=$this->update($utente->getId(),'username',$utente->getUsername());
        $e2=$this->update($utente->getId(),'password',$utente->getPass());
        $e3=$this->update($utente->getId(),'email',$utente->getEmail());
        $e4=$this->update($utente->getId(),'pIVA',$utente->getpIVA());

        if($e1 && $e2 && $e3 && $e4){
            return true;
        } else {
            return false;
        }
    }
    /**
     * metodo che restituisce un proprietario in base all'username
     */
    public function getProprietario($username)
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