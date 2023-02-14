<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Usuários > Adicionar</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <form id="formCadastro" method="post" action="<?=base_url('usuarios/store')?>">

                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nome:*</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" aria-describedby="user_nameHelp" required />
                        <div id="user_nameHelp" class="form-text">Insira aqui o nome do usuário.</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_email" class="form-label">E-mail:</label>
                        <input type="email" class="form-control" id="user_email" name="user_email" aria-describedby="user_emailHelp" required />
                        <div id="user_emailHelp" class="form-text">Insira aqui o e-mail do usuário.</div>
                    </div>

                    <div class="mb-3">
                        <label for="user_password" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="user_password" name="user_password" aria-describedby="user_passwordHelp" required />
                        <div id="user_passwordHelp" class="form-text">Insira aqui o número de base da especificação a ser filtrada.</div>
                    </div>

                    <div class="mb-3">
                        <label for="ativo" class="form-label">Status:</label>
                        <input type="checkbox" class="form-check-input" id="ativo" name="ativo" aria-describedby="ativoHelp" value="1" checked />
                        <div id="ativoHelp" class="form-text">Marque para tornar esse usuário ativo no sistema.</div>
                    </div>

                    <button type="button" onclick="validaForm()" class="btn btn-secondary">Submit</button>

                </form>

            </div>
        </div>
    </div>
</main>

