<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class titularesInpiModel extends Model
{
    protected $table = 'inpiprocessostitulares';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['id_processo','titular','pais','estado'];

    public function limpar(){
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->query('TRUNCATE TABLE '.$this->table);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}