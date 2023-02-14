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

class Inpi2 extends BaseController{

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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time',0);
        ini_set('max_input_time',0);

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        
        helper('xml');

        $cfgNiceInpiModel = new cfgNiceInpiModel();
        $configInpiModel = new configInpiModel();

        log_message('info', 'Inicializado o processamento');

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Red List');


        // gera o array de NICEs
        $nices = $cfgNiceInpiModel->findAll();
        foreach($nices as $v){
            $nc[] = $v->especificacao;
        }

        // gera o array de serviços e despachos
        $filtros = $configInpiModel->getMatriz();
        foreach($filtros as $k => $v){
            $abas[] = [$v->codigo, $v->codServico, $v->planilha, $v->id_servico, $v->paint];
            $xlsabas[$v->ordem] = $v->planilha;
        };

        //cria as abas
        foreach($xlsabas as $v){
            $worksheet = $spreadsheet->createSheet();
            $worksheet->setTitle($v);
        }

        /*
        $revistaModel = new revistasInpiModel();
        $processosModel = new processosInpiModel();
        $titularesModel = new titularesInpiModel();
        $niceModel = new niceInpiModel();
        $despachosModel = new despachosInpiModel();
        $protocolosModel = new protocolosInpiModel();
        */

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
            log_message('info', 'Carrega o XML');

            // Transformando arquivo XML em Objeto
            libxml_use_internal_errors(true);
            $xml = simplexml_load_file($file, "SimpleXMLElement", LIBXML_ERR_NONE );
            if ($xml === false) {
                echo "Erro no carregamento do XML<br />";
                foreach(libxml_get_errors() as $error) {
                    echo "\t", $error->message;
                }
                echo "<hr />Arrasta o arquivo para alguma aba do navegador que ficará mais fácil de identificar os caracteres inválidos.";
                
                exit;
            }
            
            /*

            
            $arquivo = fopen($file,'rb');
            $conteudo = file_get_contents($file);//, false, null, 0, -1);
            echo '<pre>'; var_dump($conteudo); die;
            $file_contents = html_entity_decode($conteudo, ENT_QUOTES, "utf-8");

            //$posicao = strpos($file_contents, '&a')+10;
            //echo '<pre>'.substr($file_contents,0,$posicao); die;

            $xml = simplexml_load_string($file_contents);

            */
            $numRevista = $xml['numero'];

            // Percorre o XML
            foreach($xml->processo as $processos){
                $titular = array();

                // insere os titulares do processo
                if($processos->titulares->titular){
                    foreach($processos->titulares->titular as $tt){
                        //echo '<pre>'; var_dump($tt); die;
                        //exlcui o processo se o titular for estrangeiro
                        if($tt['pais'] != 'BR'){
                            continue 2;
                        }

                        $titular[] = $tt['nome-razao-social'].' ['.$tt['pais'].'/'.$tt['uf'].']';
                    }
                }
                //echo '<pre>'; var_dump($titular); die;

                // filtra os NICE do processo
                if($processos->{'lista-classe-nice'}){
                    foreach($processos->{'lista-classe-nice'}->{'classe-nice'} as $cn){
                        //echo '<pre>'; var_dump($cn); die;
                        $especificacao = explode(';', $cn->especificacao);
                        foreach($especificacao as $esp){
                            $nice = trim($esp);
                            if($nice == ''){
                                continue;
                            }
                            if(array_search($nice,$nc)){
                                continue 3;
                            }
                        }
                    }
                }

                //echo '<pre>'; var_dump($processos); die;
                // insere os despachos do processo
                foreach($processos->despachos->despacho as $d){
                    //echo '<pre>'; var_dump($d); die;
                    $ok = 0;
                    foreach($abas as $v){
                        if($v[0] == $d['codigo']){
                            if($d->protocolo['numero']) {
                                if($v[1] == $d->protocolo['codigoServico']){
                                    $ok = 1;
                                    break;
                                }
                            } else {
                                $ok = 1;
                                break;
                            }
                        }
                    }
                    //die('- '.$ok);
                    
                    if($ok == 1){

                        switch($v[2]){
                            case 'Indeferimento':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                    'Detalhes do despacho: '.$d->{'texto-complementar'},
                                ];
                                break;
                            case 'Oposição':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                    $d->{'texto-complementar'},
                                ];
                                break;
                            case 'Arquivados':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                ];
                                break;
                            case 'Desistências':
                                if($d->protocolo->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                    $d->protocolo['numero'],
                                ];
                                break;
                            case 'Extintos':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                ];
                                break;
                            case 'Nulidade':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                if($v[3] == 6){
                                    $peticao = "Nulidade administrativa de registro de marca (336.1)";
                                }else{
                                    $peticao = "[protocolo interno] Nulidade administrativa de registro de marca (de ofício) (391.1)";
                                }

                                $texto = "Protocolo: ".$d->protocolo['numero']." (".$d->protocolo['data'].")\r\n Petição (tipo): ".$peticao."\r\n Titular(es): ".$d->protocolo->requerente['nome-razao-social']."\r\n Procurador: ".$d->protocolo->procurador."\r\n Detalhes do despacho: ".$d->{'texto-complementar'};

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                    $texto,
                                    ];
                                break;
                            case 'Caducidade':
                                $texto = "Protocolo: ".$d->protocolo['numero']." (".$d->protocolo['data'].")\r\n Petição (tipo): Caducidade (337.1)\r\n Titular(es): ".$d->protocolo->requerente['nome-razao-social']."\r\n Procurador: ".$d->protocolo->procurador;
                                
                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    $d->protocolo['numero'],
                                    implode(' / ', $titular),
                                    $texto,
                                ];
                                break;
                            case 'Merito':
                                if($processos->procurador != ''){
                                    continue 3;
                                }

                                $xls[$v[2]][] = [
                                    '',
                                    '',
                                    '',
                                    $processos['numero'],
                                    '',
                                    implode(' / ', $titular),
                                    $d->{'texto-complementar'},
                                ];
                                break;
                            }

                        //registra se precisa pintar a linha
                        $arr_paint[$v[2]][] = $v[4];
                    }

                }//fim foreach percorre os despachos do processo
            
            log_message('info', 'Concluído o processo: '.$processos['numero']);
            } // fim foreach Percorre o XML


            // cria o XLS
            log_message('info', 'Cria o XLS');
            $nome_arquivo = 'INPI - RPI '.$numRevista;
            $spreadsheet->getProperties()
                ->setCreator("Next Step Up")
                ->setTitle($nome_arquivo)
                ->setSubject("Dados extraídos da Revista de Marcas do INPI ".$numRevista);

            foreach($xls as $nomeServico => $v){
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
                
                switch($nomeServico){
                    case 'Indeferimento':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Observação');

                        $worksheet->setAutoFilter('A1:G'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Oposição':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Petição');
                        $worksheet->setAutoFilter('A1:G'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Arquivados':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ;
                        $worksheet->setAutoFilter('A1:G'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Desistências':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Petição');
                        $worksheet->setAutoFilter('A1:G'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->getStyle('G1:G'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('G')->setAutoSize(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Extintos':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome');
                        $worksheet->setAutoFilter('A1:F'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                        ->getNumberFormat()
                                        ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Nulidade':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Despacho');
                        $worksheet->setAutoFilter('A1:H'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                        ->getNumberFormat()
                                        ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);

                        $worksheet->getColumnDimension('G')->setWidth(60);
                        $worksheet->getStyle('G')->getAlignment()->setWrapText(true);
                        
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Caducidade':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Petição')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Observação');
                        $worksheet->setAutoFilter('A1:H'.count($v));
                        $worksheet->getStyle('D1:E'.count($v))
                                        ->getNumberFormat()
                                        ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->getColumnDimension('E')->setAutoSize(true);
                        $worksheet->getColumnDimension('F')->setWidth(60);
                        $worksheet->getColumnDimension('G')->setWidth(60);
                        $worksheet->getStyle('G')->getAlignment()->setWrapText(true);
                        
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    case 'Merito':
                        $worksheet
                            ->setCellValue('A1', 'Red List?')
                            ->setCellValue('B1', 'E-mail')
                            ->setCellValue('C1', 'Empresa Marca')
                            ->setCellValue('D1', 'Processo')
                            ->setCellValue('E1', 'Código')
                            ->setCellValue('F1', 'Nome')
                            ->setCellValue('G1', 'Observação');
                        $worksheet->setAutoFilter('A1:G'.count($v));
                        $worksheet->getStyle('D1:D'.count($v))
                                    ->getNumberFormat()
                                    ->setFormatCode('########000');
                        $worksheet->getColumnDimension('D')->setAutoSize(true);
                        $worksheet->getColumnDimension('F')->setWidth(60);
                        $worksheet->getColumnDimension('G')->setWidth(60);
                        $worksheet->getStyle('G')->getAlignment()->setWrapText(true);
                        $worksheet->freezePane('A2');
                        $worksheet->setSelectedCell('A2');
                        break;
                    }
                log_message('info', 'Processada a aba '.$nomeServico);
            }
        
        log_message('info', 'Salva XLS');
        //escreve o XLSX e coloca para download
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Sistema - '.$numRevista.'.xlsx"');
        $writer->save('php://output');
        exit();// precisa desse comando para não dar bug no download


        } // verifica se o arquivo está válido
    

    }



}
