<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Filtros NICE > Editar</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <form id="formCadastro" method="post" action="<?=base_url('filtrosDestaques/update')?>">
                    <input type="hidden" name="id" id="id" value="<?=$nice->id?>" />

                    <div class="mb-3">
                        <strong>Serviço:</strong> <?=$nice->servico?>
                    </div>

                    <div class="mb-3">
                        <strong>Despacho:</strong> <?=$nice->despacho?>
                    </div>

                    <div class="mb-3">
                        <label for="num_base" class="form-label">Destaque:</label>
                        <select class="form-select" name="paint" id="paint">
                            <?php
                            foreach($paints as $k => $v){
                            ?>
                            <option value="<?=$k?>" <?php if($k == $nice->paint) echo 'selected="selected"'; ?>><?=$v?></option>
                            <?php 
                            }
                            ?>
                        </select>
                        <div id="num_baseHelp" class="form-text">Selecione o tipo de destaque que queira dar.</div>
                    </div>

                    <button type="button" onclick="validaForm()" class="btn btn-secondary">Submit</button>

                </form>

            </div>
        </div>
    </div>
</main>

