<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class configInpiModel extends Model
{
    protected $table = 'cfg_servicos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['codigo','nome','planilha','procurador'];

    public $paints = [
        0 => 'Nenhum',
        1 => 'Fundo azul na linha',
        2 => 'Fundo verde na linha',
        3 => 'Fundo rosa na linha',
        4 => 'Linha com a fonte em vermelho',
        5 => 'Linha com a fonte em bold',
    ];

    public function getMatriz(){
        $this->table('cfg_servicos');
        $this->join('cfg_servicos_despachos sd', 'sd.id_servico = cfg_servicos.id', 'inner');
        $this->join('cfg_despachos d', 'd.id = sd.id_despacho', 'inner');
        $this->select("
            sd.id,
            cfg_servicos.id as id_servico,
            cfg_servicos.nome AS servico,
            cfg_servicos.codigo AS codServico,
            cfg_servicos.planilha,
            cfg_servicos.ordem,
            d.nome AS despacho,
            d.codigo,
            sd.paint
            ");
        $this->orderBy('cfg_servicos.ordem','ASC');
        //die('<pre>'.$this->getCompiledSelect());  
        return $this->findAll();
    }

    public function getItemMatriz($id){
        $this->table('cfg_servicos');
        $this->join('cfg_servicos_despachos sd', 'sd.id_servico = cfg_servicos.id', 'inner');
        $this->join('cfg_despachos d', 'd.id = sd.id_despacho', 'inner');
        $this->select("
            sd.id,
            sd.paint,
            cfg_servicos.nome AS servico,
            cfg_servicos.codigo AS codServico,
            d.nome AS despacho,
            d.codigo
            ");
        $this->where("sd.id", $id);
        //die('<pre>'.$this->getCompiledSelect()); 
        return $this->first();
    }

    public function editMatriz($data){
        $sql = "UPDATE cfg_servicos_despachos SET paint = '".$data['paint']."' WHERE id = '".$data['id']."'";
        $this->query($sql);
    }
}