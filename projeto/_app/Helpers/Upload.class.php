<?php

/**
 * Upload.class [TIPO]
 * Responsável por executar upload de imagens, arquivos e mídias no sistema!
 *  
 * @copyright (c) 2016, Grupo PWeb1 JRV
 */
class Upload {

    private $File;
    private $Name;
    private $Send;

    /** IMAGE UPLOAD */
    private $Width;
    private $Image;

    /** RESULTADOS (RETORNOS DA CLASSE) */
    private $Result;
    private $Error;

    /** DIRETÓRIOS */
    private $Folder;
    //Verifica diretório padrão de uploads
    private static $BaseDir;

    /**
     * Verifica e cria um diretório padrão de uploads no sistema!<br>
     * <b>../uploads/</b>
     */
    function __construct($BaseDir = null) {
        self::$BaseDir = ((string) $BaseDir ? $BaseDir : '../uploads/');
        //Caso não exista o diretório ele cria com permissão máxima
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
            mkdir(self::$BaseDir, 0777);
        endif;
    }

    /**
     * Envia a imagem.
     * @param FILES $Image = Enviar envelope de $_FILES com dados da imagem a ser pega
     * @param string $Name = Pega o nome do arquivo (ou artigo)
     * @param int $Width = Largura da imagem (1024 padrão)
     * @param string $Folder = Diretório para onde vai o arquivo      
     */
    public function Image(array $Image, $Name = null, $Width = null, $Folder = null) {
        $this->File = $Image;
        $this->Name = ((string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')));
        $this->Width = ((int) $Width ? $Width : 1024);
        $this->Folder = ((string) $Folder ? $Folder : 'images');

        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImage();
    }

    /**
     * Envia arquivos (exmplo = .docx, .txt)
     * @param FILES $File = Enviar envelope de $_FILES com dados do arquivo a ser pego
     * @param string $Name = Pega o nome do arquivo (ou artigo)
     * @param string $Folder = Diretório para onde vai o arquivo
     * @param int $MaxFileSize = Tamanho máximo do arquivo
     */
    public function File(array $File, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $File;
        $this->Name = ((string) $Name ? $Name : substr($File['name'], 0, strrpos($File['name'], '.')));
        $this->Folder = ((string) $Folder ? $Folder : 'files');
        $MaxFileSize = ( (int) $MaxFileSize ? $MaxFileSize : 15);

        //Formatos de aruivos aceitos
        $FileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf'
        ];

        //Validação do tamanho do arquivo
        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande, tamanho máximo de {$MaxFileSize}mb.";
        //Validando tipo de arquivo
        elseif(!in_array($this->File['type'], $FileAccept)):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo não suportado. Apenas .docx e . pdf';
        //Fazendo o upload
        else:
            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    /**
     * Envia mídia (exmplo = .docx, .txt)
     * @param FILES $Media = Enviar envelope de $_FILES com dados do arquivo a ser pego
     * @param string $Name = Pega o nome do arquivo (ou artigo)
     * @param string $Folder = Diretório para onde vai o arquivo
     * @param int $MaxFileSize = Tamanho máximo do arquivo
     */
    public function Media(array $Media, $Name = null, $Folder = null, $MaxFileSize = null) {
        $this->File = $Media;
        $this->Name = ((string) $Name ? $Name : substr($Media['name'], 0, strrpos($Media['name'], '.')));
        $this->Folder = ((string) $Folder ? $Folder : 'medias');
        $MaxFileSize = ( (int) $MaxFileSize ? $MaxFileSize : 90);

        //Formatos de aruivos aceitos
        $FileAccept = [
            'audio/mp3',
            'video/mp4'
        ];

        //Validação do tamanho do arquivo
        if ($this->File['size'] > ($MaxFileSize * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande, tamanho máximo de {$MaxFileSize}mb.";
        //Validando tipo de arquivo
        elseif(!in_array($this->File['type'], $FileAccept)):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo não suportado. Apenas audio mp3 e vídeo mp4';
        //Fazendo o upload
        else:
            $this->CheckFolder($this->Folder);
            $this->setFileName();
            $this->MoveFile();
        endif;
    }

    /**
     * Verificar upload => Executando um getResult é possível verificar
     * se o upload foi executado ou não. Retorna uma string com o caminho 
     * e nome do arquivo ou FALSE.
     * @return STRING = Caminho e nome do arquivo ou FALSE.
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * Obtém erro => Retorna um array associativo com um code, um title,
     * um erro e um tipo.
     * @return ARRAY $Error = Array associativo com o erro
     */
    public function getError() {
        return $this->Error;
    }

    // ############### MÈTODOS PRIVADOS ###############

    /**
     * Responsável pela verificação e criação dos diretórios
     * com base em tipo de arquivo, ano e mês.
     * @param string $Folder Nome do diretório a ser checado
     */
    private function CheckFolder($Folder) {
        //Pega os índices do date e armazena o ano no $y e o mês no $m
        list($y, $m) = explode('/', date('Y/m'));
        $this->CreateFolder("{$Folder}");
        $this->CreateFolder("{$Folder}/{$y}");
        $this->CreateFolder("{$Folder}/{$y}/{$m}/");
        $this->Send = ("{$Folder}/{$y}/{$m}/");
    }

    /**
     * Responsável pela verificação e criação do diretório base!
     * @param string $Folder Nome do diretório a ser criado
     */
    private function CreateFolder($Folder) {
        //Verifica que o diretório realmente não existe e então cria o diretório não existente com permissão máxima
        if (!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)):
            mkdir(self::$BaseDir . $Folder, 0777);
        endif;
    }

    /**
     * Responsável pela validação do nome do arquivo, montando o nome e tratando a string
     * A função strrchar(String Right Character) encontra a última
     * ocorrência de um caractere em uma string
     */
    private function setFileName() {
        $FileName = Check::Name($this->Name) . strrchr($this->File['name'], '.');
        //Verifica se já existe um arquivo com o mesmo nome
        if (file_exists(self::$BaseDir . $this->Send . $FileName)):
            //Renomeia o arquivo para não ficar com o mesmo nome
            $FileName = Check::Name($this->Name) . '-' . time() . strrchr($this->File['name'], '.');
        endif;
        $this->Name = $FileName;
    }

    /**
     * Realiza upload de imagens redimensionado a mesma!
     */
    private function UploadImage() {
        //Valida o tipo de arquivo (MIME type), faz uma validação mais segura pelo tipo do arquivo 
        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
                break;

            case 'image/png':
            case 'image/x-png':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
                break;
        endswitch;

        //Validação da imagem
        if (!$this->Image):
            $this->Result = false;
            $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG';
        //Validação verificando se precisa redimensionar imagem    
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $ImageX = ($this->Width < $x ? $this->Width : $x);
            $ImageH = ($ImageX * $y) / $x;

            $NewImage = imagecreatetruecolor($ImageX, $ImageH);

            //Slava imagem com o fundo transparente.
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);

            //Mover a imagem (cópia que vai para o servidor).
            //imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);

            //Validando o nome da imagem
            switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;

                case 'image/png':
                case 'image/x-png':
                    imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
            endswitch;

            //Verifica se a imagem foi criada ou não
            if (!$NewImage):
                $this->Result = false;
                $this->Error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG';
            else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = null;
            endif;

            //Limpa memória
            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }

    //Envia arquivo e mídias
    private function MoveFile() {
        //Se enviar arquivo
        if (move_uploaded_file($this->File['tmp_name'], self::$BaseDir . $this->Send . $this->Name)):
            $this->Result = $this->Send . $this->Name;
            $this->Error = null;
        else:
            $this->Result = false;
            $this->Error = 'Erro ao mover o arquivo. Favor tente mais tarde';
        endif;
    }

}
