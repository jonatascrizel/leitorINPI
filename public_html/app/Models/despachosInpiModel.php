<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class despachosInpiModel extends Model
{
    protected $table = 'inpidespachos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['id_processo','codigo','textoComplementar'];

    public function limpar(){
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->query('TRUNCATE TABLE '.$this->table);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}