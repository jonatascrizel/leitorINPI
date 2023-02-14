<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Filtros NICE > Adicionar</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <form id="formCadastro" method="post" action="<?=base_url('filtrosNice/processaXLS')?>" enctype="multipart/form-data">

                    <div class="row mb-3">
                        <label for="formFile" class="form-label">Arquivo XLSX</label>
                        <input class="form-control" type="file" id="arquivoXLSX" name="arquivoXLSX" accept=".xlsx" />
                    </div>

                    <button type="button" id="btn_xlsx" onclick="validaFormXLS()" class="btn btn-secondary">Submit</button>

                </form>

            </div>
        </div>
    </div>
</main>

