<?php

class EValutazione
{
    /**id relativo alla valutazione */
    private $id;
    /**testo della valutazione */
    private $comment;
    /**voto della valutazione */
    private $vote;
    /**id del luogo per il quale si effettua la valutazione */
    private $idplace;
    /**id dell'utente che effettua la valutazione */
    private $idvisitatore;
    /**data in cui è stato inserito il commento */
    private $data;
    /** l' ora in cui è stato inserito il commento */
    private $ora;

    public function __construct($text,$vote,$idplace,$idvisitatore,$d,$o){
        $this->comment=$text;
        $this->vote=$vote;
        $this->idplace=$idplace;
        $this->idvisitatore=$idvisitatore;
        $this->data=$d;
        $this->ora=$o;
    }
    /**
     * @return int id valutazione
     */
    public function getId():int{
        return $this->id;
    }
    /**
     * @return int idplace place
     */
    public function getIdPlace():string {
        return $this->idplace;
    }
    /**
     * @return int idvisitatore visitatore
     */
    public function getIdVisitatore():string {
        return $this->idvisitatore;
    }
    /**
     * @return string testo valutazione
     */
    public function getText ():string {
        return $this->comment;
    }
    /**
     * @return int voto valutazione
     */
    public function getVote (){
        return $this->vote;
    }
    /**
     * @return date data commento
     */
    public function getData(){
        return $this->data;
    }
    /**
     * @param date $data commento
     */
    public function setData($data){
        $this->data = $data;
    }
    /**
     * @return time ora commento
     */
    public function getOra(){
        return $this->ora;
    }
    /**
     * @param time $ora commento
     */
    public function setOra($ora){
        $this->ora = $ora;
    }

    /**
     * @param int $id valutazione
     */
    public function setId($id){
        $this->id=$id;
    }
    /**
     * @param int $place place
     */
    public function setIdPlace(string $place){
        $this->idplace=$place;
    }
    /**
     * @param int $visitatore visitatore
     */
    public function setIdVisitatore(string $visitatore){
        $this->idvisitatore=$visitatore;
    }
    /**
     * @param string $text testo valutazione
     */
    public function setText(string $text){
        $this->comment=$text;
    }
    /**
     * @param int $vote voto valutazione
     */
    public function setVote($vote){
        $this->vote=$vote;
    }

    /**
     * Stampa le informazioni delle valutazioni
     */
    public function __toString(){
        $st=" Text: ".$this->getText()." Vote: ".$this->getVote()." idLuogo: ".$this->getIdPlace()."Data Commento:".$this->getData()."\n";
        return $st;
    }

}