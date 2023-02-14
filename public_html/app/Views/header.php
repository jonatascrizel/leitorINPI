<!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="author" content="Next Step Up" />
            <title>Ferramenta de extração de dados da Revista INPI</title>

            <link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" />
            <link href="<?=base_url('assets/css/all.min.css')?>" rel="stylesheet" />
            <link href="<?=base_url('assets/css/sweetalert2.min.css')?>" rel="stylesheet" />

        </head>
        <body>

            <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
                <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="<?=base_url('')?>">Conversor INPI</a>
                <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="<?=base_url('/login/logout')?>">Logout</a>
                    </li>
                </ul>
            </header>

            <div class="container-fluid">
                <div class="row">