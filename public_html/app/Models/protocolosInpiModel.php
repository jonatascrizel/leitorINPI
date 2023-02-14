<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class protocolosInpiModel extends Model
{
    protected $table = 'inpiprotocolos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['id_despacho','numero','data','codigoServico','requerente','pais','estado','procurador'];

    public function limpar(){
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->query('TRUNCATE TABLE '.$this->table);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}