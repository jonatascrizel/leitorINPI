<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Filtros NICE > Editar</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <form id="formCadastro" method="post" action="<?=base_url('filtrosNice/update')?>">
                    <input type="hidden" name="id" id="id" value="<?=$nice->id?>" />

                    <div class="mb-3">
                        <label for="especificacao" class="form-label">Especificação:*</label>
                        <input type="text" class="form-control" id="especificacao" name="especificacao" aria-describedby="especificacaoHelp" value="<?=$nice->especificacao?>" required />
                        <div id="especificacaoHelp" class="form-text">Insira aqui o texto da especificação a ser filtrada.</div>
                    </div>

                    <div class="mb-3">
                        <label for="classe" class="form-label">Classe:</label>
                        <input type="text" class="form-control" id="classe" name="classe" aria-describedby="classeHelp" value="<?=$nice->classe?>" />
                        <div id="classeHelp" class="form-text">Insira aqui a classe da especificação a ser filtrada.</div>
                    </div>

                    <div class="mb-3">
                        <label for="num_base" class="form-label">N° de base:</label>
                        <input type="text" class="form-control" id="num_base" name="num_base" aria-describedby="num_baseHelp" value="<?=$nice->num_base?>" />
                        <div id="num_baseHelp" class="form-text">Insira aqui o número de base da especificação a ser filtrada.</div>
                    </div>

                    <button type="button" onclick="validaForm()" class="btn btn-secondary">Submit</button>

                </form>

            </div>
        </div>
    </div>
</main>

