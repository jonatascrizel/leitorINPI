<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class niceInpiModel extends Model
{
    protected $table = 'inpinice';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['id_processo','classe','especificacao'];

    public function limpar(){
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->query('TRUNCATE TABLE '.$this->table);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}