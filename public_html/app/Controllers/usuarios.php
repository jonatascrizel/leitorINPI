<?php namespace App\Controllers;

require('../vendor/autoload.php');

use App\Models\UserModel;

class usuarios extends BaseController{

    public function index()	{
        $UserModel = new UserModel();

        $data['users'] = $UserModel->orderBy('user_name', 'asc')->findAll();

		echo view('header');
		echo view('menu');
        echo view('usuarios', $data);
		echo view('footer');
	}

    public function add(){
        $data = array();

		echo view('header');
		echo view('menu');
        echo view('usuariosAdd', $data);
		echo view('footer');
    }

    public function store(){
        $UserModel = new UserModel();

        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'user_email'  => $this->request->getVar('user_email'),
            'user_password'  => password_hash($this->request->getVar('user_password'), PASSWORD_DEFAULT),
            'ativo'  => $this->request->getVar('ativo'),
            ];
        $save = $UserModel->insert($data);

        return redirect()->to(base_url('usuarios'));
    }

    public function edit($id){
        $UserModel = new UserModel();

        $data['users'] = $UserModel->where('id', $id)->first();

		echo view('header');
		echo view('menu');
        echo view('usuariosEdit', $data);
		echo view('footer');
    }

    public function update(){
        $UserModel = new UserModel();
        
        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'user_email'  => $this->request->getVar('user_email'),
            'ativo'  => $this->request->getVar('ativo'),
            ];
        //echo '<pre>'; var_dump($data); die;
        $save = $UserModel->update($this->request->getVar('id'), $data);
        
        return redirect()->to(base_url('usuarios'));
    }

    public function delete($id){
        $UserModel = new UserModel();
        $data['nice'] = $UserModel->where('id', $id)->delete();
        return redirect()->to(base_url('usuarios'));
    }


}