<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Ferramenta de extração de dados da Revista INPI</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <form class="mb-5" id="formFiltros" action="<?=base_url('inpi2/processaXML')?>" method="post" enctype="multipart/form-data">
                    <fieldset class="filtros">
                        <legend>Filtros</legend>

                        <?php
                        foreach($ar_filtros as $k1 => $v1){
                            ?>
                            <div class="mb-3">
                                <h2 class="h5"><?=$v1['nome']?> (Cod serviço: <?=$v1['codServico']?>)</h2>
                            <?php
                        /*
                        echo '<pre>';
                        var_dump($v1);
                        exit;
                        */
                            foreach($v1['despachos'] as $k2 => $v2){
                                ?>
                                <div class="form-check offset-1">
                                    <label class="form-check-label"><?=$v2['despacho']?> (Cod serviço: <?=$v2['codigo']?>)</label>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </fieldset>

                    <div class="row mb-3">
                        <label for="formFile" class="form-label">Arquivo XML:</label>
                        <input class="form-control" type="file" id="arquivoXML" name="arquivoXML" accept=".xml" />
                    </div>

                    <div class="row mb-3">
                        <button type="button" id="btn_sbm_xml" onclick="validaFormXML()" class="btn btn-primary btn-lg">Processar</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</main>

