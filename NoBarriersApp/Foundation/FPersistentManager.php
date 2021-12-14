<?php
/**
 * Lo scopo di questa classe e' quello di fornire un accesso unico al DBMS, incapsulando
 * al proprio interno i metodi di tutte le altre classi Foundation, cosi che l'accesso
 * ai dati persistenti da parte degli strati superiore dell'applicazione sia piu' intuitivo.
 * @author gruppo 4
 * @package Foundation
 */

if(file_exists('config.inc.php'))
    require_once 'config.inc.php';

require_once 'include.php';

class FPersistentManager
{
    /** l'unica istanza della classe */
    private static $instance = null;

    /**
     * Metodo che restituisce l'unica istanza dell'oggetto.
     * @return FPersistentManager l'istanza dell'oggetto.
     */
    static function getInstance(): FPersistentManager
    {
        if (static::$instance == null) {
            static::$instance = new FPersistentManager();
        }
        return static::$instance;
    }
    /**
     * Metodo che carica dal dbms informazioni in un corrispettivo oggetto Entity.
     * @param string $class il nome della classe (ottenibile tramite EClass::name )
     * @return object
     */
    public function load(string $class, int $id)
    {

        if ( class_exists( $class ) ) // si verifica che la classe esista   //"F".----------------------------------
        {
            $resource = substr($class,1); // si ricava il nome della risorsa corrispondente all'oggetto Entity
            $foundClass = 'F'.$resource;
        //    if(method_exists($foundClass, 'loadById($id)')) {
                 $method='loadById';
                $fclass=new $foundClass;
                $ris=$fclass->$method($id);
           // }
        }
        return $ris;
    }
    /** metodo che fa la store di un oggetto sul DB
     * @param object $obj oggetto da salvare
     * @return int $result
     */
    public function store($obj): int
    {
        $class = get_class($obj); // restituisce il nome della classe dall'oggetto
        $resource = substr($class, 1); // nome della risorsa (Utente, Attivita,Citta ...)
        $foundClass = 'F' . $resource; // nome della rispettiva classe Foundation
        $method = 'store'; // nome del metodo
        if (class_exists($foundClass)){
            $fclass=new $foundClass;
            $result = $fclass->$method($obj);
    }
        return $result;
    }
    /** metodo che cancella una riga in base all'id sul DB di una certa classe
     * @param string $class
     * @param int $id
     * @return bool
     */
    public function delete(string $class,$id):bool
    {
        if(class_exists($class)) {
            $resource = substr($class, 1);
            $foundClass = 'F' . $resource;
            $method = 'delete';
            $fclass = new $foundClass;
            $result = $fclass->$method($id);

        }
        return $result;

    }
    /**
     * @param $FClass classe foundation relativa all'oggetto entity
     * @param $id id dell'oggetto
     * @param $attr attributo da aggiornare
     * @param $val valore da impostare
     * @return bool esito aggiornamento
     */
    public function update($FClass,$id, $attr, $val)
    {
        if($FClass=='FVisitatore'||$FClass=='FProprietario'||$FClass=='FCategoria'||$FClass=='FCitta'||$FClass=='FAttivita'||$FClass=='FValutazione'||$FClass=='Fimgvisitatore'||$FClass=='Fimgproprietario'){
            $fclass=new $FClass;
            $result=$fclass->update($id,$attr,$val);
    }
        return $result;

    }
    /**
     * @param $id dell'oggetto
     * @param $class classe dell'oggetto
     * @return bool esito
     */
    public function exist(string $class,$id)
    {
        if (class_exists($class))
        {
            $resource = substr($class, 1);
            $foundClass = 'F' . $resource;
            $method = 'exist';
            $fclass=new $foundClass;
            $result = $fclass->$method($id);

        }
       return $result;
    }
    /**
     * Verifica se è presente un utente con un certo username  (registrazione)
     * @param $username
     * @return bool|null esito
     *
     */
    public function esisteUsername($username){                // Questo metodo vale solo per proprietario e visitatore, viene utilizzato nel login
        $pr = new FProprietario();
        $vi=new FVisitatore();
        $ret=$pr->esisteUsername($username);
        $ris=$vi->esisteUsername($username);
        if($ret)
        {
            return $ret;
        }
        if ($ris)
        {
            return $ris;
        }
        else return false;
    }

    /**
     * @param $utente oggetto EVisitatore o EProprietario
     * @return bool
     */
    public function updateDiUtente($utente) {          // è stato utilizzato per consentire la modifica del profilo
        if(is_a($utente, EVisitatore::class)) {
            $visit = new FVisitatore();
            $up = $visit->updateUtente($utente);
        }
        else {
            $pro=new FProprietario();
            $up = $pro->updateUtente($utente);
        }
        return $up;

    }
    /**
     * @param $username   string
     * @return EVisitatore | EProprietario
     */
    public function getUtenteByUsername($username)  // utilizato per restituire un oggetto EVisitatore data la username che è univoca.
    {
        $ogg1=new FVisitatore();
        $ogg2=new FProprietario();
        if ($ogg1->esisteUsername($username))
        {
            $ut=$ogg1->getVisitatore($username);
        }
        else {
            $ut=$ogg2->getProprietario($username);
        }
        return $ut;
    }

    /**Metodo che aggiorna l'immagine del profilo di un visitatore
     * @param EImmagine $foto che sostituisce quella attuale
     * @return bool $esito
     */
    public function updateFotoVisitatore($foto) {  // serve per aggiornare l' immagine del profilo
        $fu = new Fimgvisitatore();
        $esito= $fu->updateFoto($foto);
        return $esito;
    }

    /**Metodo che aggiorna l'immagine del profilo di un proprietario
     * @param EImmagine $foto che sostituisce quella attuale
     * @return bool $esito
     */
    public function updateFotoProprietario($foto) {  // serve per aggiornare l' immagine del profilo
        $fu = new Fimgproprietario();
        $esito= $fu->updateFoto($foto);
        return $esito;
    }
// sono stati necessari due metodi diversi per la modifica della foto perchè non avevo nessun attributo di EImmagine che potesse distinguere un visitatore da un proprietario

    /**
     * @return array|null delle ultime 10 valutazioni
     */

public function recuperaUltimi10Commenti() {  // utilizzato per l' homepage dell' amministratore
        $fc = new FValutazione();
        $ret = $fc->ricercaUltimiCommenti();    //restituisce un array di commenti (array di oggetti)
        return $ret;
}

    /**
     * Verifica se è presente una categoria in base al nome
     * @param $nome
     * @return bool|null esito
     *
     */
    public function esisteCategoria($nome){          // serve in fase di inserimento di una attività per verificare l' esistenza della categoria
        $vi=new FCategoria();                        // utilizzata dall' amministratore
        $ret=$vi->esisteCat($nome);
        return $ret;
    }
    /**
     * Verifica se è presente una citta in base al nome
     * @param $nome
     * @return bool|null esito
     *
     */
    public function esisteCitta($nome){
        $vi=new FCitta();
        $ret=$vi->esisteCit($nome);
        return $ret;
    }

    /***
     * @param $nome
     * @return mixed id
     */
    public function getCategoryByName($nome){   // forse si può togliere grazie all' inserimento dei menu
        $cat=new FCategoria();
        $id=$cat->getCategory($nome);
        return $id;
    }

    /**
     * @param $nome
     * @return mixed id
     */
    public function getTownyByName($nome){     // forse si può togliere
        $cat=new FCitta();
        $id=$cat->getTown($nome);
        return $id;
    }

    /**
     * @return array ECitta
     */
public function TutteLeCitta()   // visualizzate nella homepage
{
    $c=new FCitta();
    $citta=$c->ListaCitta();
    return $citta;

}

    /**
     * @return array ECategoria
     */
    public function TutteLeCategorie() // visualizzate dopo aver scelto la citta(è sempre la lista completa non viene applicato alcun filtro)
    {
        $c=new FCategoria();
        $lista=$c->ListaCategorie();
        return $lista;

    }
    public function AttivitaDaApprovare(){
        $att=new FAttivita();
        $lista=$att->UltimeAttivita();
        return $lista;
    }
    /**Restituisce la lista di attività
     * @param int $idcitta
     * @param int $idcategoria
     * @return array EAttivita $attivita
     */
    public function ListaAttivita($idcitta,$idcategoria){
        $att=new FAttivita();
        $attivita=$att->CercaAttPerCittaEcategoria($idcitta,$idcategoria);
        return $attivita;

    }
    /**Restituisce la lista di attività di un certo proprietario
     * @param int $idproprietario
     * @return array EAttivita $attivita
     */
    public function AttivitaProprietario($idproprietario){
        $att=new FAttivita();
        $attivita=$att->LeMieAttivita($idproprietario);
        return $attivita;
    }
    /**Restituisce la lista di attività modificate
     * @return array EAttivita $lista
     */
    public function AttivitaModificate(){
        $att=new FAttivitamodificata();
        $lista=$att->ListaModificate();
        return $lista;                       //un array di EAttmodificate
    }
    /**Restituisce la lista di immagini delle attività modificate
     * @return array EImmagine $lista
     */
    public function ImmaginiModificate(){
        $img=new Fimgattmodificate();
        $lista=$img->ListaModificate();
        return $lista;                      //un array di immagini modificate
    }
    /**Aggiunge l'immagine dell'attività modificata
     * @return int $id
     */
    public function AggiungiImgModificate($obj){
        $img=new Fimgattmodificate();
        $id=$img->store($obj);
        return $id;
    }
    /**Aggiunge le immagini dell'attività modificate
     * @return array EImmagine $modificate
     */
    public function GalleriaModificate($id){
        $imgm=new Fimgattmodificate();
        $modificate=$imgm->loadByIdAttivita($id);
        return $modificate;
    }

    /**
     * @param int $id dell'oggetto EAttivita
     * @return array EAttivita $attivita
     */
    public function loadModificataByIdAtt($id){
        $att=new FAttivitamodificata();
        $attivita=$att->AttModificataByIdAttivita($id);
        return $attivita;
    }
    /**
     * @param int $id dell'oggetto EAttivita
     * @return array|null EImmagine $gallery
     */
    public function loadImgModifiedByIdAtt($id){
        $img=new Fimgattmodificate();
        $gallery=$img->loadByIdAttivita($id);
        return $gallery;
    }
    /**Aggiorna descrizione e indirizzo di un'attività
     * @param EAttivita $att
     * @return bool
     */

    public function UpdateDatiAttivita($att){   //passo l' oggetto EAttivita modificata
        $attivita=new FAttivita();
        $esito=$attivita->UpdateAttivita($att);
        return $esito;
    }
    /**Aggiorna le immagini di un'attività
     * @param EAttivita $att
     * @return bool
     */

    public function UpdateImmaginiAttivita($arrayimg){  // passo l'oggetto (array di immagini), per il quale voglio sostituire i valori data e type
        $immagini=new Fimgattivita();
        $esito=$immagini->UpdateImmagini($arrayimg);
        return $esito;
    }

    /**
     * @param $idattivita di cui si vogliono cancellare le img modificate
     * @return bool
     */
    public function deleteimgModifiedByIdAtt($idattivita){
        $immagini=new Fimgattmodificate();
        $esito=$immagini->deleteGalleryByIdAtt($idattivita);
        return $esito;
    }

    /**
     * @param $idattivita di cui si vogliono cancellare i dati modificati
     * @return bool
     */
    public function deleteAttModifiedByIdAtt($idattivita){
        $att=new FAttivitamodificata();
        $esito=$att->deleteAttModifiedByIdAtt($idattivita);
        return $esito;
    }

}