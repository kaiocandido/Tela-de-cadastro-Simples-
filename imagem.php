<?php

require_once 'Pessoa.php';
class Imagem {

    
    private $pessoa;
    const DIRETORIO_IMAGEM = 'C:/xamp/htdocs/arquivo_exemplo_pratico_imagens';

    public function __construct()
    {
        $this->pessoa = new Pessoa();
    }

    public function salvarImagem($id_pessoa = null){
        $imagemAntiga = '';
        $erro = '';
        $imagemRetorno = '';
        $alteracao = !empty($id_pessoa);

        if($alteracao){
            $imagemAntiga = $this->pessoa->obterId($id_pessoa)['imagem'];
        }

        if(!empty($_FILES['imagem']['name'])){
            $retorno = $this->gravarImagem();

            if(!empty($retorno['erro'])){
                $erro = $retorno['erro'];
                $imagemRetorno = '';
            }else {
                $imagemRetorno = $retorno['imagem'];
            }

            if($alteracao && empty($erro)){
                $this->removerImagem($imagemAntiga, $id_pessoa);
            }
        }elseif (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] == '1') {
            $this->removerImagem($imagemAntiga, $id_pessoa);

            $imagemRetorno = '';

        }else{
            $imagemRetorno = $imagemAntiga;
        }
        
        $resposta = [
            'erro' => $erro,
            'imagem_retorno' => $imagemRetorno,
        ];

        return $resposta;

    }

    private function gravarImagem(){
        try {
            $retorno = [
                'erro' => '',
                'imagem' => '',
            ];

            if($_FILES['imagem']['error'] !== UPLOAD_ERR_OK){
                $retorno['erro'] = 'Falha no cadastro da imagem';

                return $retorno;
            }

            $imagemTemp = $_FILES['imagem']['tmp_name'];
            $nomeOriginal = $_FILES['imagem']['name'];

            $tipoArquivoMime = mime_content_type($imagemTemp);
            
            if(strpos($tipoArquivoMime, 'image/') == false){
                $retorno['erro'] = 'O arquivo enviado não é uma imagem valida';
                return $retorno;
            }

            $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
            $nomeUnico = uniqid('img', true) . '.' . strtolower($extensao);

            $caminhoDestino = self::DIRETORIO_IMAGEM.$nomeUnico;

            if(!is_dir(self::DIRETORIO_IMAGEM)){
                mkdir(self::DIRETORIO_IMAGEM, 0777, true);
            }

            if(!move_uploaded_file($imagemTemp, $caminhoDestino)){
                $retorno['erro'] = "Erro ao salvar a imagem";
                return $retorno;
            }

            $retorno['imagem'] = $nomeUnico;

            return $retorno;
        } catch (Exception $e) {
            $retorno['erro'] = "Falha ao cadastrar imagem, tente novamente mais tarde!!!";
            return $retorno;
        }
    }   

    private function removerImagem($imagemAntiga, $id_pessoa){
        if(!empty($id_pessoa)){
            $this->pessoa->removerImagem($id_pessoa);
        }
        
        $this->removerImagemFisica($imagemAntiga);
    }   

    private function removerImagemFisica($nomeImagem){
        $caminho = self::DIRETORIO_IMAGEM;

        if(!empty($nomeImagem) && file_exists($caminho)){
            @unlink($caminho);
        }
    }
}