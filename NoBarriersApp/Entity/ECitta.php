<?php


class ECitta
{
    private $id;
    private $nome;

    public function __construct(string $n)
    {
        $this->nome = $n;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setId(int $i)
    {
        $this->id = $i;
    }

    public function setNome($n)
    {
        $this->nome = $n;
    }

    public function __toString(): string
    {
        return $s = "id:" . $this->getId() . "\n" . "nome città:" . $this->getNome() . "\n";
    }

    /*------------------------------------------METODI DI VALIDAZIONE-------------------------------------------------------*/

    /**
     * @param $nome sting nome
     * @return bool
     */
    static function validaCitta($nome)   // verifica che la citta inserita sia unica (non si può aggiungere due volte la stessa città)
    {
        $pm = FPersistentManager::getInstance();
        $esito = $pm->esisteCitta($nome);
        if ($esito) {
            return false;
        } else {
            return true;
        }
    }

}