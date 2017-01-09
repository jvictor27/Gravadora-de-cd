<?php

/**
 * Check.class [Helper]
 * Classe responsável por manipuar e validar dados do sistema.
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class Check {

    private static $Data;
    private static $Format;

    /**
     * <b>Verificar E-mail:</b> Executa validação de formato de e-mail. Se for um email válido retorn true se não false.
     * @param STRING $Email = Uma conta de e-mail
     * @return BOOL = True para um email válido, ou false
     */
    public static function Email($Email) {
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/
';

        if (preg_match(self::$Format, self::$Data)):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * <b>Trasforma URL:</b> Transforma uma string no formato de URL amigável e retorna a string convertida
     * @param STRING $Name = Uma string qualquer
     * @return STRING $Data = Uma URL amigável válida
     */
    public static function Name($Name) {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª'
        ;
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }

    /**
     * <b>Trasforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP
     * @param STRING $Name = Data no formato (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING $Data = Data no formato TIMESTAMP
     */
    public static function Data($Data) {
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);

        if (empty(self::$Format[1])):
            self::$Format[1] = date('H:i:s');
        endif;

        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
        return self::$Data;
    }

    /**
     * <b>Limita as palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string
     * @param STRING $String = Uma string qualquer
     * @return INT $Limite = String limitada pelo limite
     */
    public static function Words($String, $Limite, $Pointer = null) {
        self::$Data = strip_tags(trim($String));
        self::$Format = (int) $Limite;

        $ArrayWords = explode(' ', self::$Data);
        $NumWords = count($ArrayWords);
        $NewWords = implode(' ', array_slice($ArrayWords, 0, self::$Format));

        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer);
        $Result = (self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data);

        //var_dump($ArrayWords, $NumWords, $NewWords);
        return $Result;
    }

    /**
     * <b>Obter categoria:</b> Informe o name (url) de uma categoria para obter o ID da mesma
     * @param STRING $category = URL da categoria
     * @return STRING $category_id = id da categoria informada
     */
    public static function CatByName($CategotyName) {
        $read = new Read;
        $read->ExeRead('ws_categories', "WHERE category_name = :name", "name={$CategotyName}");
        if ($read->getRowCount()):
            return $read->getResultado()[0]['category_id'];
        else:
            echo "A categoria {$CategotyName} não foi encontrada!";
            die();
        endif;
    }

    /**
     * <b>Usuários Online:</b> Ao exeutar este HELPER ele automaticamente deleta os usuário espirados e 
     * executa um READ para obter quantos usuários estão realmente online no momento
     * @return INT = Quantidade de usuários online
     */
    public static function UserOnline() {
        $now = date('Y-m-d H:i:s');
        $deleteUserOnline = new Delete;
        $deleteUserOnline->ExeDelete('ws_siteviews_online', "WHERE online_endview < :now", "now={$now}");

        $readUserOnline = new Read();
        $readUserOnline->ExeRead('ws_siteviews_online');
        return $readUserOnline->getRowCount();
    }

    /**
     * <b>Trasforma URL:</b> Ao executar este HELPER ele automaticamente verifica a existência da imagem da pasta
     * uploads. Se existir retorna a imagem redimensionada.
     * @return HTML = imagem redimensionada
     */
    public static function Image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null) {
        self::$Data = $ImageUrl;

        if (file_exists(self::$Data) && !is_dir(self::$Data)):
            $patch = BASE;
            $imagem = self::$Data;
            //return $patch . $imagem;
            return "<img src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }

}
