<?php namespace App\Controllers;

require('../vendor/autoload.php');

use CodeIgniter\Controller;
use App\Models\revistasInpiModel;

 
class Dashboard extends Controller
{
    public function index()
    {
        $session = session();
        $data = array();
        $revistaModel = new revistasInpiModel();

        $data['revista'] = $revistaModel->where('ok IS NOT NULL')->first();

		echo view('header');
		echo view('menu');
        echo view('dashboard', $data);
		echo view('footer');
    }

    public function getArquivo(){
        helper('getarquivo');

        $destination = ('E:/xampp7.2/htdocs/fabianoTecnologia/public_html/writable/uploads/baixado.zip');
        $url = 'http://revistas.inpi.gov.br/txt/Pehehe.zip';
        $fileInfo = getFileInfo($url);
        //echo '<pre>'; echo var_dump($fileInfo).'<br />';
        if($fileInfo['http_code'] == 200 && $fileInfo['download_content_length'] < 1024*1024 && $fileInfo['url'] == $url){
            if(fileDownload($url, $destination)){
                echo "File dowloaded.<hr>";

                $zip = new \ZipArchive;
                $res = $zip->open($destination);
                if ($res === true) {
                    $path = "E:/xampp7.2/htdocs/fabianoTecnologia/public_html/writable/uploads/";
                    $zip->extractTo($path);
                    $zip->close();
                    echo 'Zip extracted';
                }
            }
         
        }

    }
}