<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista Pessoa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Ícones Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php 
    require 'Pessoa.php';
    require 'Imagem.php';

    $imagem = new Imagem();

    $pessoa = new Pessoa();

    if(isset($_GET['id'])){
      $id_pessoa = htmlspecialchars($_GET['id']);
      $dadosPessoa = $pessoa->obterId($id_pessoa);
      $imagem = $dadosPessoa['imagem'];
      $tem_imagem = !empty($imagem);
      $caminhoImg = $tem_imagem  ? 'imagens/'.$imagem : '#';

    }

    if(isset($_POST['NOME'])){
      $dados['imagem'] = '';
      $dados['nome'] = htmlspecialchars($_POST['nome']);
      $resposta = $imagem->salvarImagem();

      if(!empty($resposta['erro'])){
        $erro = $resposta['erro'];
      }else {
        $dados['imagem'] = $resposta['imagem'];
      }

      if(empty($erro)){
        
        if(!empty($id_pessoa)){
          $dados['id_pessoa'] = $id_pessoa;
          $res = $pessoa->alterar($dados);
        }else {
          $id_pessoa = $pessoa->inserir($dados);

          if(!empty($id_pessoa)){
            $res = true;
          }else{
            $res = false;
          }

        }

        if(!$res){
          $erro = "erro ao salvar dados da pessoa";
        }else {
          header("location: lista_pessoas.php");
        }
      }

    }
  ?>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <!-- Card do formulário -->
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center"><i class="bi bi-person-plus"></i> Novo Cadastro</h3>
            
            <?php 
              if(!empty($erro)){
              ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php $erro?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
              </div>
            <?php
            }
            ?>

            <form method="POST" enctype="multipart/form-data">
              <!-- Nome -->
              <div class="mb-3">
                <label for="nome" class="form-label fw-semibold">Nome</label>
                <input class="form-control" type="text" id="nome" name="nome" required value="<?= $dadosPessoa['nome'] ?? '' ?>">
              </div>

              <!-- Imagem -->
              <div class="mb-3">
                <label for="imagem" class="form-label fw-semibold">Imagem (opcional)</label>
                <input class="form-control" type="file" id="imagem" name="imagem" accept="image/*">

                <!-- Pré-visualização -->
                <div id="preview-container" class="mt-3 position-relative <?= $tem_imagem ? '' : 'd-none' ?>" style="max-width: 200px;">
                  <input type="hidden" id="remover_imagem_flag" name="remover_imagem" value="0">
                  <img id="preview-imagem"
                       src="<?= $caminhoImg ?>"
                       class="img-thumbnail shadow-sm rounded"
                       style="max-width: 100%; height: auto;"
                       alt="Pré-visualização">
                  <button type="button" id="remover-imagem" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="transform: translate(30%, -30%); border-radius: 50%;">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
                <!-- Campo oculto para manter a imagem anterior -->
                <input type="hidden" name="imagem_antiga" value=""">
              </div>

              <!-- Botões -->
              <div class="d-flex justify-content-between mt-4">
                <!-- Botão Voltar -->
                <a href="lista_pessoas.php" class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left"></i> Voltar
                </a>

                <!-- Botão Enviar -->
                <button type="submit" name="enviar" value="enviar" class="btn btn-primary px-4">
                  <i class="bi bi-upload"></i> Enviar
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const inputImagem = document.getElementById('imagem');
    const previewContainer = document.getElementById('preview-container');
    const previewImagem = document.getElementById('preview-imagem');
    const btnRemover = document.getElementById('remover-imagem');

    inputImagem.addEventListener('change', function() {
      const file = this.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImagem.src = e.target.result;
          previewContainer.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
      } else {
        previewImagem.src = '';
        previewContainer.classList.add('d-none');
      }
    });

    btnRemover.addEventListener('click', function() {
      inputImagem.value = '';
      previewImagem.src = '';
      previewContainer.classList.add('d-none');
      document.getElementById('remover_imagem_flag').value = '1';
    });
  </script>
</body>
</html>
