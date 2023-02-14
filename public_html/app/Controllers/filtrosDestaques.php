<?php namespace App\Controllers;

require('../vendor/autoload.php');

use App\Models\configInpiModel;

class filtrosDestaques extends BaseController{

    public function index()	{
        $cfgNiceModel = new configInpiModel();

        $data['nices'] = $cfgNiceModel->getMatriz();
        $data['paints'] = $cfgNiceModel->paints;

		echo view('header');
		echo view('menu');
        echo view('filtrosDestaques', $data);
		echo view('footer');
	}

    public function edit($id){
        $cfgNiceModel = new configInpiModel();

        $data['nice'] = $cfgNiceModel->getItemMatriz($id);
        $data['paints'] = $cfgNiceModel->paints;
        //echo '<pre>'; var_dump($data); die;

		echo view('header');
		echo view('menu');
        echo view('filtrosDestaquesEdit', $data);
		echo view('footer');
    }

    public function update(){
        $cfgNiceModel = new configInpiModel();
        
        $data = [
            'id' => $this->request->getVar('id'),
            'paint' => $this->request->getVar('paint'),
            ];
        $save = $cfgNiceModel->editMatriz($data);
        
        return redirect()->to(base_url('filtrosDestaques'));
    }


}