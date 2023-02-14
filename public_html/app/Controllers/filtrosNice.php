<?php namespace App\Controllers;

require('../vendor/autoload.php');

use App\Models\cfgNiceInpiModel;

class filtrosNice extends BaseController{

    public function index()	{
        $cfgNiceModel = new cfgNiceInpiModel();

        $data['nices'] = $cfgNiceModel->orderBy('especificacao', 'asc')->findAll();

		echo view('header');
		echo view('menu');
        echo view('filtrosNice', $data);
		echo view('footer');
	}

    public function add(){
        $data = array();

		echo view('header');
		echo view('menu');
        echo view('filtrosNiceAdd', $data);
		echo view('footer');
    }

    public function store(){
        $cfgNiceModel = new cfgNiceInpiModel();

        $data = [
            'especificacao' => $this->request->getVar('especificacao'),
            'classe'  => $this->request->getVar('classe'),
            'num_base'  => $this->request->getVar('num_base'),
            ];
        $save = $cfgNiceModel->insert($data);

        return redirect()->to(base_url('filtrosNice'));
    }

    public function edit($id){
        $cfgNiceModel = new cfgNiceInpiModel();

        $data['nice'] = $cfgNiceModel->where('id', $id)->first();

		echo view('header');
		echo view('menu');
        echo view('filtrosNiceEdit', $data);
		echo view('footer');
    }

    public function update(){
        $cfgNiceModel = new cfgNiceInpiModel();
        
        $data = [
            'especificacao' => $this->request->getVar('especificacao'),
            'classe'  => $this->request->getVar('classe'),
            'num_base'  => $this->request->getVar('num_base'),
            ];
        //echo '<pre>'; var_dump($data); die;
        $save = $cfgNiceModel->update($this->request->getVar('id'), $data);
        
        return redirect()->to(base_url('filtrosNice'));
    }

    public function delete($id){
        $cfgNiceModel = new cfgNiceInpiModel();
        $data['nice'] = $cfgNiceModel->where('id', $id)->delete();
        return redirect()->to(base_url('filtrosNice'));
    }

    public function import(){
        $data = [];

		echo view('header');
		echo view('menu');
        echo view('filtrosNiceImport', $data);
		echo view('footer');
    }

    public function processaXLS(){
        $cfgNiceModel = new cfgNiceInpiModel();

        // faz upload do arquivo
        $file = $this->request->getFile('arquivoXLSX');

        // verifica se o arquivo está válido
        if (! $file->isValid()){
            throw new \RuntimeException($file->getErrorString().'('.$file->getError().')');
        }else{
            // valida se é um arquivo XML
            $type = $file->getClientMimeType();
            if ($type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
                throw new \Exception('Você precisa enviar um arquivo XLS!');
            }

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            $spreadsheet = $reader->load($file);

            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            //echo '<pre>'; var_dump($sheetData);
            foreach($sheetData as $v){
                //echo '<pre>'; var_dump($v); die;
                $data = [
                    'classe' => $v['A'],
                    'especificacao' => $v['B'],
                    'num_base' => $v['C'],
                ];
                $cfgNiceModel->insert($data);
            }
            
        }
    }


}