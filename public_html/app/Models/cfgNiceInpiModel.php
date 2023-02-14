<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class cfgNiceInpiModel extends Model
{
    protected $table = 'cfg_nice';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['classe','especificacao','num_base'];

    public function filterNice($id_processo){
        $this->join('inpinice i', 'cfg_nice.especificacao = i.especificacao', 'inner');
        $this->select("COUNT(*) as resultado");
        $this->where("i.id_processo", $id_processo);

        return $this->first();
    }
}