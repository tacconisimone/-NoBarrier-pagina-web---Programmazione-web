<?php

require_once 'include.php';
class EAttivitamodificata
{
    /** identificativo */
    private $id ;
    /** informazioni */
    private $descrizione;
    /** indirizzo */
    private $indirizzo;
    private $idattivita;
    public function __construct($desc,$ind,$att){
        $this->descrizione=$desc;
        $this->indirizzo=$ind;
        $this->idattivita=$att;
    }
    public function getId(){
        return $this->id;
    }
    public function getDescrizione():string
    {
        return $this->descrizione;
    }
    public function getIndirizzo():string
    {
        return $this->indirizzo;
    }
    public function getIdAttivita(){
        return $this->idattivita;
    }
    public function setId(int $num)
    {
        $this->id=$num;
    }
    public function setDescrizione(string $d)
    {
        $this->descrizione=$d;
    }
    public function setIndirizzo(string $i)
    {
        $this->indirizzo=$i;
    }
    public function setIdAttivita(int $num)
    {
        $this->idattivita=$num;
    }


}