<?php

/**
 * Admincd.class [MODEL ADMIN]
 * Responsável por gerenciar os cd no Admin do sistema. 
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class AdminCDs {

    //$Data = dados.
    private $Data;
    private $CD;
    private $Error;
    private $Resultado;

    //Nome da tebela no banco de dados
    const Entity = 'ws_cds';

    /**
     * <b>Cadastrar o CD:</b> Envelope os dados do cd em um array atribuitivo e execute esse método
     * para cadastrar o cd. Envia a capa automaticamente! 
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeCreate(array $Data) {
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            $this->Error = ["Erro ao cadastrar: Para cadastrar um cd, favor preencha todos os campos!", WS_ALERT];
            $this->Resultado = false;
        else:
            $this->setData();

            //Imagens
            if ($this->Data['cd_cover']):
                $upload = new Upload;
                $upload->Image($this->Data['cd_cover']);
            endif;

            if (isset($upload) && $upload->getResult()):
                $this->Data['cd_cover'] = $upload->getResult();
                $this->Create();
            else:
                $this->Data['cd_cover'] = null;
                $this->Create();
            endif;
        endif;
    }

    /**
     * <b>Atualizar o cd:</b> Envelope os dados em um array atribuitivo e informe o id de um
     * cd para atualiza-lo na tabela
     * @param INT $cdId = Id no cd
     * @param ARRAY $Data = Atribuitivo
     */
    public function ExeUpdate($CDId, array $Data) {
        $this->CD = (int) $CDId;
        $this->Data = $Data;

        if (in_array('', $this->Data)):
            var_dump($this->Data);
            $this->Error = ["Para atualizar este cd, preencha todos os campos ( Capa não precisa ser preenchido! ).", WS_ALERT];
            $this->Resultado = false;
        else:
            $this->setData();

            //Bloco referente ao reenvio da capa do cd
            if (is_array($this->Data['cd_cover'])):
                $readCapa = new Read();
                $readCapa->ExeRead(self::Entity, "WHERE cd_id = :cd", "cd={$this->CD}");
                $capa = '../uploads/' . $readCapa->getResultado()[0]['cd_cover'];
                if (file_exists($capa) && !is_dir($capa)):
                    unlink($capa);
                endif;
                $uploadCapa = new Upload();
                $uploadCapa->Image($this->Data['cd_cover']);
            endif;

            if (isset($uploadCapa) && $uploadCapa->getResult()):
                $this->Data['cd_cover'] = $uploadCapa->getResult();
                $this->Update();
            else:
                unset($this->Data['cd_cover']);
                $this->Update();
            endif;

        endif;
    }

    /**
     * <b>Deleta cd:</b> Informe o ID do cd a ser removido para que esse método realize uma checagem de
     * pastas e galerias excluinto todos os dados nessesários!
     * @param INT $cdId = Id do cd
     */
    public function ExeDelete($CDId) {
        $this->CD = (int) $CDId;

        $ReadCD = new Read();
        $ReadCD->ExeRead(self::Entity, "WHERE cd_id = :cd", "cd={$this->CD}");
        if (!$ReadCD->getResultado()):
            $this->Error = ["O cd que você tentou deletar não existe no sistema.", WS_ERROR];
            $this->Resultado = false;
        else:
            $CDDelete = $ReadCD->getResultado()[0];
            if (file_exists('../uploads/' . $CDDelete['cd_cover']) && !is_dir('../uploads/' . $CDDelete['cd_cover'])):
                unlink('../uploads/' . $CDDelete['cd_cover']);
            endif;

            $deleta = new Delete();
            // $deleta->ExeDelete("ws_cds_gallery", "WHERE cd_id = :gbcd", "gbcd={$this->cd}");
            $deleta->ExeDelete(self::Entity, "WHERE cd_id = :cdid", "cdid={$this->CD}");

            $this->Error = ["O cd <b>{$CDDelete['cd_title']}</b> foi removido com sucesso do sistema!", WS_INFOR];
            $this->Resultado = true;
        endif;
    }

    /**
     * <b>Verifica o cadastro:</b> Retorna ID do registro se o cadastro for efetuado ou FALSE de não
     * Para evrificar erros execute o getError(). 
     * @param BOOL $Var = InsertID ou False
     */
    function getResultado() {
        return $this->Resultado;
    }

    /**
     * <b>Obter erro:</b> Retroan um arrey associativo com uma mensagem e o tipo de erro
     * @param ARRAY $Data = Atribuitivo
     */
    function getError() {
        return $this->Error;
    }

    /**
     * **********************************************
     * ************* Métodos Privados ***************
     * **********************************************
     */
    //Valida e cria os dados para realizar o cadastro
    private function setData() {
        $Cover = $this->Data['cd_cover'];

        unset($this->Data['cd_cover']);

        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);

        $this->Data['cd_title'] = Check::Name($this->Data['cd_title']);
        $this->Data['cd_date'] = Check::Data($this->Data['cd_date']);
        $this->Data['cd_type'] = 'cd';
        $this->Data['cd_cover'] = $Cover;
    }

    private function Create() {
        $cadastra = new Create();
        $cadastra->ExeCreate(self::Entity, $this->Data);
        if ($cadastra->getResultado()):
            $this->Error = ["O cd <b>{$this->Data['cd_title']}</b> foi cadastrado com sucesso no sistema.", WS_ACCEPT];
            $this->Resultado = $cadastra->getResultado();
        endif;
    }

    private function Update() {
        $Update = new Update();
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE cd_id = :id", "id={$this->CD}");
        if ($Update->getResultado()):
            $this->Error = ["O cd <b>{$this->Data['cd_title']}</b> foi atualizado com sucesso no sistema.", WS_ACCEPT];
            $this->Resultado = true;
        endif;
    }

}
