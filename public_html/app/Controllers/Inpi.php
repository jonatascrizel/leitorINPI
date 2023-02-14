<?php namespace App\Controllers;


require('../vendor/autoload.php');

use App\Models\configInpiModel;
use App\Models\cfgNiceInpiModel;
use App\Models\revistasInpiModel;
use App\Models\processosInpiModel;
use App\Models\titularesInpiModel;
use App\Models\niceInpiModel;
use App\Models\despachosInpiModel;
use App\Models\protocolosInpiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Inpi extends BaseController{

    public function index()	{
        $configInpiModel = new configInpiModel();

        $filtros = $configInpiModel->getMatriz();

        foreach($filtros as $k => $v){
            $ar_filtros[$v->id_servico]['nome'] = $v->servico;
            $ar_filtros[$v->id_servico]['codServico'] = $v->codServico;
            $ar_filtros[$v->id_servico]['despachos'][] = array(
                                                        'despacho' => $v->despacho,
                                                        'codigo' => $v->codigo,
                                                        );
        }

        $data['ar_filtros'] = $ar_filtros;

		echo view('header');
		echo view('menu');
        echo view('inpi', $data);
		echo view('footer');
	}

    public function processaXML(){
        helper('xml');
        $revistaModel = new revistasInpiModel();
        $processosModel = new processosInpiModel();
        $titularesModel = new titularesInpiModel();
        $niceModel = new niceInpiModel();
        $despachosModel = new despachosInpiModel();
        $protocolosModel = new protocolosInpiModel();


        // faz upload do arquivo
        $file = $this->request->getFile('arquivoXML');

        // verifica se o arquivo está válido
        if (! $file->isValid()){
            throw new \RuntimeException($file->getErrorString().'('.$file->getError().')');
        }else{
            // valida se é um arquivo XML
            $type = $file->getClientMimeType();
            if ($type != "text/xml"){
                throw new \Exception('Você precisa enviar um arquivo XML!');
            }

            // limpa o banco da aplicação
            $protocolosModel->limpar();
            $despachosModel->limpar();
            $titularesModel->limpar();
            $niceModel->limpar();
            $processosModel->limpar();
            $revistaModel->limpar();
    

            // Transformando arquivo XML em Objeto
            $xml = simplexml_load_file($file);

            // adiciona a revista no banco
            $data = [
                    'numero' => $xml['numero'],
                    'data' => implode('-',array_reverse(explode('/',$xml['data']))),
            ];
            $id_revista = $revistaModel->insert($data);

            // Percorre o XML
            foreach($xml->processo as $processos){
                $dt = [
                        'id_revista' => $id_revista,
                        'processo' => $processos['numero'],
                        'procurador' => $processos->procurador,
                ];
                $id_processo = $processosModel->insert($dt);

                // insere os titulares do processo
                if($processos->titulares->titular){
                    foreach($processos->titulares->titular as $tt){
                        //echo '<pre>'; var_dump($titular); die;
                        $dt = [
                            'id_processo' => $id_processo,
                            'titular' => $tt['nome-razao-social'],
                            'pais' => $tt['pais'],
                            'estado' => $tt['uf'],
                        ];
                        $titularesModel->insert($dt);
                    }
                }

                // insere os NICE do processo
                if($processos->{'lista-classe-nice'}){
                    foreach($processos->{'lista-classe-nice'}->{'classe-nice'} as $cn){
                        //echo '<pre>'; var_dump($cn); die;
                        $especificacao = explode(';', $cn->especificacao);
                        foreach($especificacao as $esp){
                            $nice = trim($esp);
                            if($nice == ''){
                                continue;
                            }
                            $dt = [
                                'id_processo' => $id_processo,
                                'classe' => $cn['codigo'],
                                'especificacao' => $nice,
                            ];
                            $niceModel->insert($dt);
                        }
                    }
                }

                // insere os despachos do processo
                foreach($processos->despachos->despacho as $d){
                    //echo '<pre>'; var_dump($titular); die;
                    $dt = [
                        'id_processo' => $id_processo,
                        'codigo' => $d['codigo'],
                        'textoComplementar' => $d->{'texto-complementar'},
                    ];
                    $id_despacho = $despachosModel->insert($dt);

                    //echo '<pre>'; var_dump($d->protocolo); die;
                    if($d->protocolo['numero']) {
                        $p = [
                            'id_despacho' => $id_despacho,
                            'numero' => $d->protocolo['numero'],
                            'data' =>  implode('-',array_reverse(explode('/',$d->protocolo['data']))),
                            'codigoServico' => $d->protocolo['codigoServico'],
                            'requerente' => $d->protocolo->requerente['nome-razao-social'],
                            'pais' => $d->protocolo->requerente['pais'],
                            'estado' => $d->protocolo->requerente['estado'],
                            'procurador' => $d->protocolo->procurador,
                        ];
                        $protocolosModel->insert($p);
                    }
                }//fim foreach percorre os despachos do processo

            } // fim foreach Percorre o XML

            //se terminou o script, salva ok na tabela de revistas
            $data = ['ok' => time()];
            $revistaModel->update($id_revista, $data);

            //a fim de reduzir o tempo e evitar timeout sem saber onde parou
            //reireciona pro dashboard
            return redirect()->to('/');

            //dierciona pro XLS
            //return redirect()->to('exportaXLS/'.$xml['numero']); 
        } // verifica se o arquivo está válido
    

    }


    public function exportaXLS($revistaNumero){
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $configInpiModel = new configInpiModel();
        $cfgNiceInpiModel = new cfgNiceInpiModel();
        $revistaModel = new revistasInpiModel();
        $processosModel = new processosInpiModel();

        //busca a revista
        $revista = $revistaModel->where('numero', $revistaNumero)->first();
        if (!$revista){
            throw new \Exception('Esta revista não está no sistema!');
        }
        //echo '<pre>'; var_dump($revista);

        // cria o XLS
        $nome_arquivo = 'INPI - RPI '.$revistaNumero;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Next Step Up")
            ->setTitle($nome_arquivo)
            ->setSubject("Dados extraídos da Revista de Marcas do INPI ".$revistaNumero);
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Red List');


        $cfg = $configInpiModel->orderBy('ordem', 'asc')->findall();
        foreach($cfg as $servico){
            //echo '<pre>'; var_dump($servico); die;
            //echo $servico->nome.'<br>';

            $nome = 'worksheet'.$servico->id;

            switch($servico->id){
                case 1:
                   // Indeferimento
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome')
                        ->setCellValue('G1', 'Observação');
                    // linha inicial dos dados
                    $ln[1] = 2;
                    break;
                case 2:
                    // Oposição
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome')
                        ->setCellValue('G1', 'Petição');
                    // linha inicial dos dados
                    $ln[2] = 2;
                    break;
                case 3:
                    // Arquivados
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome')
                        ;
                    // linha inicial dos dados
                    $ln[3] = 2;
                    break;
                case 4:
                    // Desistências
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome')
                        ->setCellValue('G1', 'Petição');
                    // linha inicial dos dados
                    $ln[4] = 2;
                    break;
                case 5: 
                    // Extintos
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome');
                    // linha inicial dos dados
                    $ln[5] = 2;
                    break;
                case 6:
                    // Nulidade
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Código')
                        ->setCellValue('F1', 'Nome')
                        ->setCellValue('G1', 'Despacho');
                    // linha inicial dos dados
                    $ln[6] = 2;
                    break;
                case 7:
                    // Caducidade
                    $$nome = $spreadsheet->createSheet();
                    $$nome->setTitle($servico->planilha);

                    $$nome
                        ->setCellValue('A1', 'Red List?')
                        ->setCellValue('B1', 'E-mail')
                        ->setCellValue('C1', 'Empresa Marca')
                        ->setCellValue('D1', 'Processo')
                        ->setCellValue('E1', 'Petição')
                        ->setCellValue('F1', 'Nome')
                        ->setCellValue('G1', 'Observação');
                    // linha inicial dos dados
                    $ln[7] = 2;
                    break;
            }// fim switch
        }// fim foreach dos serviços



        //busca dados de todos os processos que estão selecionados nas tabelas de configurações
        $processos = $processosModel->busca_dados();
        //$query = $processosModel->getLastQuery()->getQuery(); echo (string)$query; die;
        //echo '<pre>'; var_dump($processos); die;
        foreach($processos as $v){
            // se titular for de fora do país (removido na SQL) não precisa listar
            if($v->titular == ""){
                continue;
            }

            // filtra os NICE
            $registros = $cfgNiceInpiModel->filterNice($v->id_processo);
            if($registros->resultado > 0){
                continue;
            }

            switch($v->id_servico){
                case 1:
                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        'Detalhes do despacho: '.$v->observacao,
                        ];
                    break;
                case 2:
                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        $v->observacao,
                        ];
                    break;
                case 3:
                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        ];
                    break;
                case 4:
                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        $v->peticao,
                        ];
                    break;
                case 5:
                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        ];
                    break;
                case 6:
                case 8:
                    //gambiarra para o servico "protocolo interno" ficar dentro de nulidades
                    if($v->id_servico == 6){
                        $peticao = "Nulidade administrativa de registro de marca (336.1)";
                    } else {
                        $v->id_servico = 6;
                        $peticao = "[protocolo interno] Nulidade administrativa de registro de marca (de ofício) (391.1)";
                        //die('ok - '.$v->id_servico);
                    }

                    $texto = "Protocolo: ".$v->peticao." (".implode('/',array_reverse(explode('-',$v->data))).")\r\n Petição (tipo): ".$peticao."\r\n Titular(es): ".$v->requerente."\r\n Procurador: ".$v->procurador."\r\n Detalhes do despacho: ".$v->observacao;

                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        '',
                        $v->titular,
                        $texto,
                        ];
                    break;
                case 7:
                    $texto = "Protocolo: ".$v->peticao." (".implode('/',array_reverse(explode('-',$v->data))).")\r\n Petição (tipo): Caducidade (337.1)\r\n Titular(es): ".$v->requerente."\r\n Procurador: ".$v->procurador;

                    $arr_paint[$v->planilha][] = $v->paint;
                    $arr_planilha[$v->planilha][] = [
                        '',
                        '',
                        '',
                        $v->processo,
                        $v->peticao,
                        $v->titular,
                        $texto,
                        ];
                    break;
            }
        }

        foreach($arr_planilha as $nomeServico => $v){
            $worksheet = $spreadsheet->setActiveSheetIndexByName($nomeServico);

            //insere o array de uma só vez na planilha
            $worksheet->fromArray($v, NULL, 'A2');

            foreach($arr_paint[$nomeServico] as $l => $p){
                //se for para pintar a linha
                if($p == 1){
                    $worksheet->getStyle('A'.($l+1).':G'.($l+1))->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF68bbf7');
                }else if($p == 2){
                    $worksheet->getStyle('A'.($l+1).':G'.($l+1))->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF5effb1');
                } else if($p == 3){
                    $worksheet->getStyle('A'.($l+1).':G'.($l+1))->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFff9e9e');
                } else if($p == 4){
                    $worksheet->getStyle('A'.($l+1).':G'.($l+1))->getFont()
                    ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                } else if($p == 5){
                    $worksheet->getStyle('A'.($l+1).':G'.($l+1))->getFont()->setBold(true);
                }
            }
            
        }

            
        /*
        
            $nome = 'worksheet'.$servico->id;
            $$nome = $spreadsheet->setActiveSheetIndexByName($v->planilha);
            switch($v->id_servico){
                case 1:
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ->setCellValue('G'.$ln[$v->id_servico], 'Detalhes do despacho: '.$v->observacao);
                    break;
                case 2:
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ->setCellValue('G'.$ln[$v->id_servico], $v->observacao);
                    break;
                case 3:
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ;
                    break;
                case 4:
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ->setCellValue('G'.$ln[$v->id_servico], $v->peticao);
                    break;
                case 5:
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ;
                    break;
                case 6:
                case 8:
                    //gambiarra para o servico "protocolo interno" ficar dentro de nulidades
                    if($v->id_servico == 6){
                        $peticao = "Nulidade administrativa de registro de marca (336.1)";
                    } else {
                        $v->id_servico = 6;
                        $peticao = "[protocolo interno] Nulidade administrativa de registro de marca (de ofício) (391.1)";
                        //die('ok - '.$v->id_servico);
                    }

                    $texto = "Protocolo: ".$v->peticao." (".implode('/',array_reverse(explode('-',$v->data))).")\r\n Petição (tipo): ".$peticao."\r\n Titular(es): ".$v->requerente."\r\n Procurador: ".$v->procurador."\r\n Detalhes do despacho: ".$v->observacao;
                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], '')
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ->setCellValue('G'.$ln[$v->id_servico], $texto)
                        ;

                    break;
                case 7:
                    $texto = "Protocolo: ".$v->peticao." (".implode('/',array_reverse(explode('-',$v->data))).")\r\n Petição (tipo): Caducidade (337.1)\r\n Titular(es): ".$v->requerente."\r\n Procurador: ".$v->procurador;

                    $$nome
                        ->setCellValue('A'.$ln[$v->id_servico], '')
                        ->setCellValue('B'.$ln[$v->id_servico], '')
                        ->setCellValue('C'.$ln[$v->id_servico], '')
                        ->setCellValue('D'.$ln[$v->id_servico], $v->processo)
                        ->setCellValue('E'.$ln[$v->id_servico], $v->peticao)
                        ->setCellValue('F'.$ln[$v->id_servico], $v->titular)
                        ->setCellValue('G'.$ln[$v->id_servico], $texto)
                        ;
                    break;
            }


            //se for para pintar a linha
            if($v->paint == 1){
                $$nome->getStyle('A'.$ln[$v->id_servico].':G'.$ln[$v->id_servico])->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF68bbf7');
            }else if($v->paint == 2){
                $$nome->getStyle('A'.$ln[$v->id_servico].':G'.$ln[$v->id_servico])->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF5effb1');
            } else if($v->paint == 3){
                $$nome->getStyle('A'.$ln[$v->id_servico].':G'.$ln[$v->id_servico])->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFff9e9e');
            } else if($v->paint == 4){
                $$nome->getStyle('A'.$ln[$v->id_servico].':G'.$ln[$v->id_servico])->getFont()
                ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
            } else if($v->paint == 5){
                $$nome->getStyle('A'.$ln[$v->id_servico].':G'.$ln[$v->id_servico])->getFont()->setBold(true);
            }
            

            // incrementa o contador de linhas
            $ln[$v->id_servico]++;
        }

        */

        //formata o excel
        foreach($cfg as $servico){
            $nome = 'worksheet'.$servico->id;
            $$nome = $spreadsheet->setActiveSheetIndexByName($servico->planilha);

            switch($servico->id){
                case 1:
                   // Indeferimento
                    $$nome->setAutoFilter('A1:G'.count($arr_paint[$servico->planilha]));
                    $$nome->getStyle('D1:D'.$arr_paint[$servico->planilha])
                                ->getNumberFormat()
                                ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                       break;
                case 2:
                    // Oposição
                    $$nome->setAutoFilter('A1:G'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:D'.$arr_paint[$servico->planilha])
                                ->getNumberFormat()
                                ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
                case 3:
                    // Arquivados
                    $$nome->setAutoFilter('A1:G'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:D'.$ln[3])
                                ->getNumberFormat()
                                ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
                case 4:
                    // Desistências
                    $$nome->setAutoFilter('A1:G'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:D'.$arr_paint[$servico->planilha])
                                ->getNumberFormat()
                                ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->getStyle('G1:G'.$arr_paint[$servico->planilha])
                                ->getNumberFormat()
                                ->setFormatCode('########000');
                    $$nome->getColumnDimension('G')->setAutoSize(true);
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
                case 5:
                    // Extintos 
                    $$nome->setAutoFilter('A1:F'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:D'.$arr_paint[$servico->planilha])
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
                case 6:
                    $$nome->setAutoFilter('A1:H'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:D'.$arr_paint[$servico->planilha])
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);

                    $$nome->getColumnDimension('G')->setWidth(60);
                    $$nome->getStyle('G')->getAlignment()->setWrapText(true);
                    
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
                case 7:
                    $$nome->setAutoFilter('A1:H'.$arr_paint[$servico->planilha]);
                    $$nome->getStyle('D1:E'.$arr_paint[$servico->planilha])
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                    $$nome->getColumnDimension('D')->setAutoSize(true);
                    $$nome->getColumnDimension('E')->setAutoSize(true);
                    $$nome->getColumnDimension('F')->setWidth(60);
                    $$nome->getColumnDimension('G')->setWidth(60);
                    $$nome->getStyle('G')->getAlignment()->setWrapText(true);
                    
                    $$nome->freezePane('A2');
                    $$nome->setSelectedCell('A2');
                    break;
            }
        }


        //escreve o XLSX e coloca para download
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$nome_arquivo.'.xlsx"');
        $writer->save('php://output');
        // precisa desse comando para não dar bug no download
        exit();
    }


}
