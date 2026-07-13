<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Lista de Pessoas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Ícones Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <?php 
    require 'Pessoa.php';

    $pessoa = new Pessoa();

    $lista = $pessoa->obter();

  ?>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Card de listagem -->
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <h3 class="mb-4 text-center"><i class="bi bi-people-fill"></i> Lista de Pessoas</h3>

            <?php 
              if(empty($lista)){
              ?>
                <div class="alert alert-warning"><i class="bi bi-info-circle"></i> Nenhuma pessoa cadastrada.</div>
              <?php
              }else{
                ?>
                <div class="table-responsive">
                  <table class="table table-striped align-middle">
                      <thead class="table-primary">
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nome</th>
                          <th scope="col" class="text-center">Ações</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php   
                          foreach($lista as $linha){
                        ?>
                          <tr>
                            <td><?php echo $linha['id_pessoa']  ?></td>
                            <td><?php echo $linha['nome']  ?></td>
                            <td class="text-center">
                                <a href="index.php?id=<?php echo $linha['id_pessoa']  ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-search"></i>
                                </a>
                          </td>
                          </tr>
                        <?php
                        }
                        ?>
                        
                      </tbody>
                  </table>
                </div>
              <?php
              }
              ?>
            <div class="d-flex justify-content-end mt-3">
              <a href="index.php" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Novo Cadastro
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
