<?php

/**
 * Login [Models]
 * Responsável por autenticar, validar e checar usuário do sistema de login
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class Login {

    private $Level;
    private $Email;
    private $Senha;
    private $Error;
    private $Resultado;

    /**
     * Informar Level: Informe o nivel de acesso mínimo paraárea a ser protegida
     * @param INT $Level = Nível mínimo para acesso
     */
    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    /**
     * Efetuar login: Envelope um array atribuindo com índices STRING user[email], STRING pass.
     * Ao passar ese array na ExeLogin() os dados são verificados e o login é feio.
     * @param ARRAY $UserData = user [email], pass
     */
    public function ExeLogin(array $UserData) {
        $this->Email = (string) strip_tags(trim($UserData['user']));
        $this->Senha = (string) strip_tags(trim($UserData['pass']));
        $this->setLogin();
    }

    /**
     * Verificar login: Executando um getResultado é possível verificar se foi ou não efetuado
     * o acesso com os dados.
     * @return BOOLEAN V = true para login e false para erro. 
     */
    function getResultado() {
        return $this->Resultado;
    }

    /**
     * Obter erro: Retorna um array associativo com uma mensagem e um tipo de erro WS_.
     * @return ARRAY $Error = Array associativo com o erro.
     */
    function getError() {
        return $this->Error;
    }

    /**
     * Checar login: Execute esse método para verificar a sessão USERLOGIN e revalidar o acesso
     * para proteger telas restritas.
     * @return BOOLEAN $login = Retorna true ou mata a sessão e retorna false.
     */
    public function CheckLogin() {
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['user_level'] < $this->Level):
            unset($_SESSION['userlogin']);
            return false;
        else:
            return true;
        endif;
    }

    // ########## MÈTODOS PRIVADOS ##########

    /**
     * Valida os dados e armazena os erros caso exisa. Executa o login.
     */
    private function setLogin() {
        if (!$this->Email || !$this->Senha || !Check::Email($this->Email)):
            $this->Error = ['Informe seu E-mail e senha para efetuar o login.', WS_INFOR];
            $this->Resultado = false;
        elseif (!$this->getUser()):
            $this->Error = ['Dados informados não cadastrados.', WS_ALERT];
            $this->Resultado = false;
        elseif ($this->Resultado['user_level'] < $this->Level):
            $this->Error = ["Desculpe {$this->Resultado['user_name']}, você não tem permissão para acessar essa área.", WS_ERROR];
            $this->Resultado = false;
        else:
            echo "Logar aqui!";
            $this->Execute();
        endif;
    }

    /**
     * Verifica usuário e senha no banco de dados
     */
    private function getUser() {
        $this->Senha = md5($this->Senha);

        $read = new Read;
        $read->ExeRead("ws_users", "WHERE user_email = :e AND user_password = :p", "e={$this->Email}&p={$this->Senha}");
        if ($read->getResultado()):
            $this->Resultado = $read->getResultado()[0];
            return true;
        else:
            return false;
        endif;
    }

    /**
     * Executa o login armazenando a sessão
     */
    private function Execute() {
        if (!session_id()):
            session_start();
        endif;

        $_SESSION['userlogin'] = $this->Resultado;
        $this->Error = ["Olá {$this->Resultado['user_name']}, seja bem vindo(a)! Favor aguarde redirecionamento.", WS_ACCEPT];
        $this->Resultado = true;
    }

}
