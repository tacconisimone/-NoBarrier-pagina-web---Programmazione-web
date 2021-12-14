<?php
/**
 *Questa classe generalizza Eimgattivita ed Eimgutente
 */

class EImmagine
{

    /**id dell'immagine*/
    private $id;

    /**dati immagine*/
    private $data;

    /** mime type dell'immagine */
    private $type;
    /** id di una classe esterna */
    private $idoggetto;

    /**costruttore*/
    public function __construct($d, $t)
    {
        $this->data = $d;
        $this->type = $t;

    }

    /**
     * @return int id dell'immagine
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id dell'immagine
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return longblob data immagine
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param longblob $data immagine
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return string content type dell'immagine
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type dell'immagine
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
    public function  getIdoggetto()
    {
        return $this->idoggetto;
    }
    public function setIdoggetto(int $id)
    {
        $this->idoggetto=$id;
    }

    /**
     * Stampa le informazioni dell'immagine
     */
    public function __toString(){
        $st = "ID: ".$this->id."content-type: ".$this->type." Data: ".$this->data."IDesterno".$this->getIdoggetto();
        return $st;
    }


}