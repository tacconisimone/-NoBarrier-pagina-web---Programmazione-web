<?php


class ECategoria
{
    private $id;
    private $nome;
    public function __construct(string $n)
    {
        $this->nome=$n;
    }
    public function getId():int
    {
        return $this->id;
    }
    public function getNome():string
    {
        return $this->nome;
    }
    public function setId(int $i)
    {
        $this->id=$i;
    }
    public function setNome($n)
    {
        $this->nome=$n;
    }
    public function __toString():string
    {
        return $s="id:".$this->getId()."\n"."nome categoria:".$this->getNome()."\n";
    }
    /*-------------------------------------------------METODI DI VALIDAZIONE-------------------------------------------------------------*/
    /**
     * @param $nome sting nome
     * @return bool
     */
    static function validaCategoria($nome)   // verifica che la categoria inserita sia unica (non si può aggiungere due volte la stessa categoria)
    {
        $pm = FPersistentManager::getInstance();
        $esito = $pm->esisteCategoria($nome);
        if ($esito) {
            return false;
        } else {
            return true;
        }
    }

}