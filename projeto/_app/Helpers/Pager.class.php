<?php

/**
 * Pager.class [HELPER]
 * Realiza a gestão e a paginação de resultados do sistema. 
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class Pager {

    /** DEFINE O PAGER  */
    private $Page;
    private $Limite;
    private $Offset;

    /** DEFINE A LEITURA  */
    private $Tabela;
    private $Termos;
    private $Places;

    /** DEFINE O PAGINATOR  */
    private $Rows;
    private $Link;
    private $MaxLinks;
    private $First;
    private $Last;

    /** RENDERIZA O PAGINATOR  */
    private $Paginator;

    function __construct($Link, $First = null, $Last = null, $MaxLinks = null) {
        $this->Link = (string) $Link;
        $this->First = ((string) $First ? $First : 'Primeira Página');
        $this->Last = ((string) $Last ? $Last : 'Última Página');
        $this->MaxLinks = ((int) $MaxLinks ? $MaxLinks : 5);
    }

    public function ExePager($Page, $Limite) {
        $this->Page = ((int) $Page ? $Page : 1);
        $this->Limite = (int) $Limite;
        $this->Offset = ($this->Page * $this->Limite) - $this->Limite;
    }

    public function ReturnPage() {
        if ($this->Page > 1):
            $numPage = $this->Page - 1;
            header("Location: {$this->Link}{$numPage}");
        endif;
    }

    function getPage() {
        return $this->Page;
    }

    function getLimite() {
        return $this->Limite;
    }

    function getOffset() {
        return $this->Offset;
    }

    public function ExePaginator($Tabela, $Termos = null, $ParseString = null) {
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        $this->Places = (string) $ParseString;
        $this->getSyntax();
    }

    //Obtém a paginação de resultados
    public function getPaginator() {
        return $this->Paginator;
    }

    //MÉTODOS PRIVADOS

    private function getSyntax() {
        $read = new Read;
        $read->ExeRead($this->Tabela, $this->Termos, $this->Places);
        $this->Rows = $read->getRowCount();

        if ($this->Rows > $this->Limite):
            $Paginas = ceil($this->Rows / $this->Limite);
            $MaxLinks = $this->MaxLinks;

            $this->Paginator = "<ul class=\"paginator\">";
            $this->Paginator .= "<li><a title=\"{$this->First}\" href=\"{$this->Link}1\">{$this->First}</a></li>";

            for ($InicialPag = $this->Page - $MaxLinks; $InicialPag <= $this->Page - 1; $InicialPag ++):
                if ($InicialPag >= 1):
                    $this->Paginator .= "<li><a title=\"Página {$InicialPag}\" href=\"{$this->Link}{$InicialPag}\">{$InicialPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><span class=\"active\">{$this->Page}</span></li>";

            for ($DepoisPag = $this->Page + 1; $DepoisPag <= $this->Page + $MaxLinks; $DepoisPag ++):
                if ($DepoisPag <= $Paginas):
                    $this->Paginator .= "<li><a title=\"Página {$DepoisPag}\" href=\"{$this->Link}{$DepoisPag}\">{$DepoisPag}</a></li>";
                endif;
            endfor;

            $this->Paginator .= "<li><a title=\"{$this->Last}\" href=\"{$this->Link}{$Paginas}\">{$this->Last}</a></li>";
            $this->Paginator .= "</ul>";
        endif;
    }

}
