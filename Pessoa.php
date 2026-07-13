<?php

class Pessoa {

    private $conexao;

    public function __construct(){
        try {
             $dbname = 'AULA_IMAGENS';
            $host = 'localhost';
            $user = 'root';
            $password = '';
            $this->conexao = new PDO('mysql:dbname=' . $dbname. ';host=' . $host, $user, $password );
        } catch (PDOException $e) {
            null;
        } catch(Exception $e){
            null;
        }
    }

    public function obter(){
        try{
            $sql = $this->conexao->query("SELECT id_pessoa, nome, imagem FROM pessoa");
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $dados;
        }catch (PDOException $e) {
            return array();
        } catch(Exception $e){
            return array();
        }
    }

    public function obterId($idPessoa){
        try{
            $sql = $this->conexao->prepare("SELECT id_pessoa, nome, imagem FROM pessoas WHERE id_pessoa = : id_pessoa");
            $sql->bindValue("id_pessoa", $idPessoa);
            $sql->execute();
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);

            return $dados;
        }catch (PDOException $e) {
           return array();
        } catch(Exception $e){
            return array();
        }
    }

    public function inserir($dados){
        try{
            $sql = $this->conexao->prepare("INSERT INTO pessoa (nome, imagens) VALUES (:nome, :imagem) ");
            $sql->bindValue(":nome", $dados['nome']);
            $sql->bindValue(":imagem", $dados['imagem']);
            $sql->execute();
            
            $idPessoa = $this->conexao->lastInsertId();

            return $idPessoa;
        }catch (PDOException $e) {
            return null;
        } catch(Exception $e){
            return null;
        }
    }

    public function alterar($dados){
        try{
            $sql = $this->conexao->prepare("UPDATE pessoa SET nome = :nome, imagem = :imagem WHERE id_pessoa = :id_pessoa)");
            $sql->bindValue(":nome", $dados['nome']);
            $sql->bindValue(":imagem", $dados['imagem']);
            $sql->bindValue(":id_pessoa", $dados['id_pessoa']);

            $sql->execute();

            return true;
        }catch (PDOException $e) {
            return false;
        } catch(Exception $e){
            return false;
        }
    }

    public function removerImagem($idPessoa){
        try{
            $sql = $this->conexao->prepare("UPDATE pessoa SET imagem = NULL WHERE id_pessoa = :id_pessoa)");

            $sql->bindValue(":id_pessoa", $idPessoa);

            $sql->execute();

        }catch (PDOException $e) {
            null;
        } catch(Exception $e){
            null;
        }
    }
}
