<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 vh-100">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Filtros Destaques/Despacho</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Serviço</th>
                                <th>Despacho</th>
                                <th>Destaque</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($nices as $k => $n){
                            ?>
                            <tr>
                                <td><?=($k+1)?></td>
                                <td><?=$n->servico?></td>
                                <td><?=$n->despacho?></td>
                                <td><?=$paints[$n->paint]?></td>
                                <td>
                                    <a href="<?=base_url('/filtrosDestaques/edit/'.$n->id)?>" title="Editar"><i class="fas fa-edit"></i></a>
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

