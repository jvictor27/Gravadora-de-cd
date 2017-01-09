<?php

/**
 * AdminCategory [MODEL ADMIN]
 * Responsável por gerenciar as cantors do sistema no admin.
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class AdminCantor {

    private $Data;
    private $CatId;
    private $Error;
    private $Resultado;

    //Nome da tablea no banco de dados;
    const Entity = 'ws_cantor';

    /**
     * <b>Cadastrar cantor:<b> Envelope título, descrição, date e sessão em um array atribuitivo e executa
     * para cadastrar a cantor. Caso seja uma sessão, envie o category_parent com STRING null.
     * @param array $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Resultado = false;
            $this->Error = ['<b>Erro ao cadastrar:</b> Para cadastrar um cantor, preencha todos os campos!', WS_ALERT];
        else:
            $this->setData();
            $this->Create();
        endif;
    }

    /**
     * <b>Atualizar cantor:<b> Envelope os dados em um array atrubuitivo e informe o id de uma
     * cantor para atualiza´la.
     * @param int 4CategoryId = Id da cantor.
     * @param array $Data = Atribuitivo
     */
    public function ExeUpdate($CantorId, array $Data) {
        $this->CatId = (int) $CantorId;
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Resultado = false;
            $this->Error = ["<b>Erro ao atualizar:</b> Para atualizar a cantor {$this->Data['category_title']} preencha todos os campos!", WS_ALERT];
        else:
            $this->setData();
            $this->setName();
            $this->Update();
        endif;
    }

    /**
     * <b>Deleta cantor:<b> Informe o ID de uma cantor para remove-la do sistema. Esse método verifica
     * o tipo de cantor e se é permitido excluir de acordo com os registros do sistema.
     * @param INT $CantorId = ID da cantor
     */
    public function ExeDelete($CantorId) {
        $this->CatId = (int) $CantorId;

        $read = new Read();
        $read->ExeRead(self::Entity, "WHERE cantor_id = :delid", "delid={$this->CatId}");

        if (!$read->getResultado()):
            $this->Resultado = false;
            $this->Error = ['Oppsss, você tentou remover uma cantor que não existe no sistema!', WS_INFOR];
        else:
            extract($read->getResultado()[0]);
            
                $delete = new Delete();
                $delete->ExeDelete(self::Entity, "WHERE cantor_id = :deletaid", "deletaid={$this->CatId}");
                
                $tipo = ('cantor');
                $this->Resultado = true;
                $this->Error = ["O <b> {$tipo} {$cantor_title}</b> foi deletada com sucesso do sistema.", WS_ACCEPT];
            
        endif;
    }

    /**
     * <b>Verifica Cadastro:</b> Retorna true se o cadastro ou update foe efeutado, retorna false se não.
     * Para ver os erros execute getError();
     * @return BOOL $var = True or False.
     */
    function getResultado() {
        return $this->Resultado;
    }

    /**
     * <b>Obter erro:</b> Retorna um array associativo com a mensagem e o tipo o erro.
     * @return array $Error = Aray associativo com a mensagem e o tipo de erro.
     */
    function getError() {
        return $this->Error;
    }

    /*
     * **************************************
     * ********** MÉTODOS PRIVADOS **********
     * **************************************
     */

    //Valida e cria os dados para realizar o cadastro
    private function setData() {
        //Pega cada índite do array $Data e executa a função strip_tags nele, limpando todos os índices.
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['cantor_title'] = Check::Name($this->Data['cantor_title']);
    }

    //Verifica artigos da cantor
    private function checkCDs() {
        $readPosts = new Read();
        $readPosts->ExeRead("ws_cds", "WHERE cd_cantor = :cantor", "cantor={$this->CatId}");
        if ($readPosts->getResultado()):
            return false;
        else:
            return true;
        endif;
    }

    //Cadastra cantor no banco
    private function Create() {
        $Create = new Create();
        $Create->ExeCreate(self::Entity, $this->Data);
        if ($Create->getResultado()):
            $this->Resultado = $Create->getResultado();
            $this->Error = ["<b>Sucesso:</b> O cantor {$this->Data['cantor_title']} foi cadastrado no sistema.", WS_ACCEPT];
        endif;
    }

    //Atualiza cantor
    private function Update() {
        $Update = new Update;
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE cantor_id = :catid", "catid={$this->CatId}");
        if ($Update->getResultado()):
            $tipo = ('cantor');
            $this->Resultado = true;
            $this->Error = ["<b>Sucesso:</b> O {$tipo} {$this->Data['cantor_title']} foi atualizado no sistema.", WS_ACCEPT];
        endif;
    }

}
