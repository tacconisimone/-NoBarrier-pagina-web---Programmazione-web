<?php
//ho tolto momentaneamente i metodi get e set relativi alle immagini che andranno forse nuovamente aggiunti
require_once 'include.php';
class EAttivita
{
    /** identificativo */
    private $id;
    /** nome attività*/
    private $nome;
    /** informazioni */
    private $descrizione;
    /** categoria di appartenenza */
    private $categoria;
    /** un insieme di commenti e voti */
    /* private $valutazione=array();*/
    /** insieme di foto */
    /* private $immagine;*/
    /**immagine principale*/
    /* private $imgesposta;*/


    /** insieme di commenti e voti
     * @AttributeType Array<EValutazione>
     */
    private $valutazione;

    /** insieme di foto
     * @AttributeType Array<EImmagine>
     */
    private $immagine;

    /**immagine principale*/
    //private $imgesposta;

    /** città */
    private $citta;
    /** posizione */
    private $indirizzo;
    /** nome del proprietario */
    private $proprietario;
    /**data in cui è stata inserita l' attività */
    private $data;
    /** l' ora in cui è stata inserita l' attività */
    private $ora;
    /** visibilita dell' attivita sull' applicazione */
    private $visibility;    //i form di pubblicazione degli utenti hanno visibility=false, solo l' amministratore può impostarla a true

    /** costruttore */
    public function __construct($n, $desc, $cat, $cit, $ind, $p, $d, $o)
    {
        $this->nome = $n;
        $this->descrizione = $desc;
        $this->categoria = $cat;
        $this->citta = $cit;
        $this->indirizzo = $ind;
        $this->proprietario = $p;
        $this->valutazione = array();
        /*$this->imgesposta;*/
        $this->immagine = array();
        $this->data = $d;
        $this->ora = $o;
        // $this->visibility=false;

    }

    /**
     * @return int id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string nome
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescrizione(): string
    {
        return $this->descrizione;
    }

    public function getCategoria(): ECategoria
    {
        $cat = new ECategoria($this->categoria->getNome());
        $cat->setId($this->categoria->getId());
        return $cat;
    }

    public function getCitta(): ECitta
    {
        $cit = new ECitta($this->citta->getNome());
        $cit->setId($this->citta->getId());
        return $cit;
    }

    public function getIndirizzo(): string
    {
        return $this->indirizzo;
    }

    public function getProprietario(): EProprietario
    {
        $pro = new EProprietario($this->proprietario->getUserName(), $this->proprietario->getPass(), $this->proprietario->getEmail(), $this->proprietario->getpIVA());
        $pro->setId($this->proprietario->getId());
        $pro->setImmagine(clone $this->proprietario->getImmagine());     // immagine passata per valore e non per riferimento, se ne fa una copia esatta
        return $this->proprietario;
    }

    /**
     * @return int visibilità attivita
     */
    public function getVis()
    {
        return $this->visibility;
    }

    /*public function getImmagini():EImmagine{
        return  $this->immagine;
    }
    public function setImmagini(EImmagine $img){
        $this->immagine=$img;
    }
    */
    public function setId(int $num)
    {
        $this->id = $num;
    }

    public function setNome(string $n)
    {
        $this->nome = $n;
    }

    public function setDescrizione(string $d)
    {
        $this->descrizione = $d;
    }

    public function setCategoria(ECategoria $c)
    {
        $this->categoria = $c;
    }

    public function setCitta(ECitta $ci)
    {
        $this->citta = $ci;
    }

    public function setIndirizzo(string $i)
    {
        $this->indirizzo = $i;
    }

    public function setProprietario(EProprietario $p)
    {
        $this->proprietario = $p;
    }

    /**
     * @return date data inserimento attività
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param date $data inserimento attività
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return time ora inserimento attività
     */
    public function getOra()
    {
        return $this->ora;
    }

    /**
     * @param time $ora inserimento attività
     */
    public function setOra($ora)
    {
        $this->ora = $ora;
    }

    /**
     *  Setta la visibilità dell' attività
     */
    public function setVis($vis)
    {                       //setHid serve all' utente per fare la store, l' amministratore per approvare fa update, setVis serve nella load dal db per recuperare il valore aggiornato
        $this->visibility = $vis;    //true
    }

    /**
     *  Oscura l' attività
     */
    public function setHid()
    {
        $this->visibility = 0;   //false
    }



    /**
     * @return mixed
     */
    /*
    public function getImgesposta():EImmagine
    {
        return clone $this->imgesposta;
    }
*/
    /**
     * @param mixed $imgesposta
     */
    /*
    public function setImgesposta(EImmagine $imgesposta): void
    {
        $this->imgesposta = $imgesposta;
    }

-------------------------------------------------------------------------------------------------------------------*/

    /**
     * @return array<EValutazione> elenco di commenti e voti
     */
    public function getValutazioni()
    {
        return $this->valutazione;
    }

    /**
     * @param $val array<EValutazione>
     */
    public function setValutazioni(array $val)
    {
        $this->valutazione = $val;
    }

    /**
     * Aggiunge una valutazione (commento e voto)
     * @param $vals EValutazione aggiunge una valutazione all'attività
     */
    public function addValutazioni(EValutazione $vals)
    {
        array_push($this->valutazione, $vals);
    }

    /**
     * Elimina una valutazione
     * @param int $pos posizione della valutazione da eliminare dell'array
     */
    public function removeValutazione($pos)
    {
        unset($this->valutazione[$pos]);
        $this->valutazione = array_values($this->valutazione);
    }

    /**
     * Calcola il punteggio medio dei voti ricevuti
     * @return int $number media delle valutazioni
     */
    public function averageMark()
    {
        $sum = 0;
        $avarege = 0;
        $n_mark = is_array($this->valutazione) ? count($this->valutazione) : 0;
        if ($n_mark > 1)
            foreach ($this->valutazione as $rec) {
                $sum = $sum + $rec->getVote();
                $avarege = $sum / $n_mark;
            }
        elseif (null !== $this->valutazione[0]->getVote())
            $avarege = $this->valutazione[0]->getVote();
        $number=intval($avarege);
        //$numberAsString = number_format($avarege, 1);
        return $number;
    }

    /**
     * @return array<EImmagine> elenco di foto
     */
    public function getImmagini()
    {
        return $this->immagine;
    }

    /**
     * @param $img array<EImmagine>
     */
    public function setImmagini(array $img)
    {
        $this->immagine = $img;
    }

    /**
     * Aggiunge una foto
     * @param $imgs EImmagine aggiunge una foto all'attività
     */
    public function addImmagini(EImmagine $imgs)
    {
        array_push($this->immagine, $imgs);
    }

    /**
     * Elimina una foto
     * @param int $pos posizione della foto da eliminare dell'array
     */
    public function removeImmagine($pos)
    {
        unset($this->immagine[$pos]);
        $this->immagine = array_values($this->immagine);
    }

    public function __toString()
    {
        $comm = implode(",", $this->valutazione);
        return "nome attività=" . $this->getNome() . "/n" . "descrizione=" . $this->getDescrizione() . "/n" . "proprietario=" . $this->getProprietario() . "categoria=" . $this->getCategoria() . "/n" . "valutazioni=" . $comm . "data=" . $this->getData() . "/n";
    }

    /**
     * @param $attmodificate array di oggetti EAttivitamodificata
     * @param $immagini  arrai di oggetti EImmagine
     * Questo metodo verrà utilizzato da CAmministratore per recuperare un array di oggetti EAttivita
     * ovvero per prendere tutte le attività che sono state modificate
     * si evidenziano i 3 casi
     * 1) Gli utenti hanno modificato solo i dati (descrizione e indirizzo)
     * 2)Gli utenti hanno modificato solo le immagini
     * 3)Gli utenti hanno modificato sia i dati che le immagini, in questo lo stesso utente potrebbe
     *   aver apportato entrambe le modifiche per la stessa attività, il metodo
     *   IdAttivitaModificate evita di avere valori ripetuti
     */

    static function ListaAttivitaModificate($attmodificate,$immagini)   //torna un array di EAttivita
    {
        $pm = FPersistentManager::getInstance();
        $attivita = array();
         if (empty($attmodificate)) {
            foreach ($immagini as $i) {
                $idatt = $i->getIdoggetto();
                $idattivita[]=$idatt;
            }
             $idnoripetuti=array_unique($idattivita);
            foreach($idnoripetuti as $id) {
                $att=$pm->load('EAttivita', $id);
                $attivita[]=$att;
            }

        } else if (empty($immagini)) {
            foreach ($attmodificate as $a) {
                $idatt = $a->getIdAttivita();   //metodo in EAttivitaModificate
                $att = $pm->load('EAttivita', $idatt);
                $attivita[]=$att;

            }
        } else {
            $idatt = static::IdAttivitaModificate($attmodificate,$immagini);
            foreach ($idatt as $id) {
                $att = $pm->load('EAttivita', $id);
                $attivita[]=$att;
                }
        }
         return $attivita;   //dagli id dell' attività ho creato un' array di oggetti EAttivita da mostrare nella Lista delle attività modificate
    }


    /**
     * @param $attmodificate
     * @param $immagini
     * @return array|int  un'array di id di EAttivita
     * Questo metodo recupera tutti gli id dell' array di EAttivitamodificata,
     * tutti gli id di EImmagini, verifica se ci sono valori comuni per non avere ripetizioni
     */
    static function IdAttivitaModificate($attmodificate,$immagini){

        $idattivita=array();
        foreach ($attmodificate as $a){
                $id = $a->getIdAttivita();
                $idattivita[]=$id;                  //array di id EAttivita (da attivita modificate)

        }
        $idimgrip=array();
        foreach($immagini as $i){
            $id=$i->getIdoggetto();
            $idimgrip[]=$id;                      //array di id EAttivita (da immagini modificate)
        }
        $idimg=array_unique($idimgrip);
        $iduguali=array_intersect($idattivita,$idimg);         // trovo gli id di quelle EAttivita di cui sono stati modificati sia i dati che le immagini
        $idunici=array_unique($iduguali);               //elimino i valori ripetuti
        $idmodify=$idunici;                           //creo un/array di id EAttivita per fare in modo che se una att ha subito entrambe le modifiche venga visualizzata una sola volta
                                                      // e prendo anche le attività che hanno subito una sola modifica
        foreach($idattivita as $id){
            if(!in_array($id,$iduguali)){
                $idmodify[]=$id;
            }
        }
        foreach($idimg as $id){
            if(!in_array($id,$iduguali)){
                $idmodify[]=$id;
            }
        }
        return $idmodify;         // id EAttivita di dutte le attività che sono state modificate
    }

    /**
     * Serve per capire dalla homepage dell' amministratore quali modifiche ha subito l' attività selezionata
     * @param $attmodif array di oggetto EAttivitamodificata
     * @param $imgmodif
     * @param $idatt
     * @return $tipo string che indica il tipo di modifica
     */

static function TipoDiModificaAttivita($attmodif,$imgmodif,$idatt){
    $tipo='';
    $immagini = array();
    if (!empty($imgmodif)) {
        foreach ($imgmodif as $i) {
            $idimg = $i->getIdoggetto();
            $idattivita[]=$idimg;
        }
        $immagini=array_unique($idattivita);  //array con da immagini con tutti gli id attivita

    }
    $attivita=array();
    if (!empty($attmodif)) {
        foreach ($attmodif as $a) {
            $id = $a->getIdAttivita();   //metodo in EAttivitaModificate
            $attivita[]=$id;   //array da attmodif con tutti gli id att
        }
    }
    $idcomuny=array_intersect($attivita,$immagini);
    $idnoripetuti=array_unique($idcomuny);
    if(in_array($idatt,$idnoripetuti)){
        $tipo='comuni';
    }
    else if(in_array($idatt,$attivita)){
        $tipo='attivita';
    }
    else {
        $tipo='immagini';
    }
    return $tipo;

}


}


