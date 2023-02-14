<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Filtros NICE</h1>
        <div class="btn-toolbar mb-2 mb-md-0"> 
            <a href="<?=base_url('/filtrosNice/import')?>" class="btn btn-sm btn-outline-secondary me-2">
            <i class="far fa-file-excel"></i>
                Importar
            </a>
            <a href="<?=base_url('/filtrosNice/add')?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-plus-circle"></i>
                Adicionar
            </a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Especificação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($nices as $k => $n){
                            ?>
                            <tr>
                                <td><?=($k+1)?></td>
                                <td><?=$n->especificacao?></td>
                                <td>
                                    <a href="<?=base_url('/filtrosNice/edit/'.$n->id)?>" title="Editar"><i class="fas fa-edit"></i></a>
                                    <a href="<?=base_url('/filtrosNice/delete/'.$n->id)?>" title="Deletar"><i class="fas fa-minus-circle"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</main>

