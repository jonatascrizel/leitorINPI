<?php namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class processosInpiModel extends Model
{
    protected $table = 'inpiprocessos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';

    protected $allowedFields = ['id_revista','processo','procurador'];

    public function limpar(){
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->query('TRUNCATE TABLE '.$this->table);
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function busca_dados(){
        $this->table('inpiprocessos');
        $this->join('inpidespachos as d', 'inpiprocessos.id = d.id_processo', 'inner');
        $this->join('cfg_despachos as cfg_d', 'd.codigo = cfg_d.codigo', 'inner');
        $this->join('cfg_servicos_despachos as cfg_sd', 'cfg_d.id = cfg_sd.id_despacho', 'inner');
        $this->join('cfg_servicos as cfg_s', 'cfg_s.id = cfg_sd.id_servico', 'inner');
        $this->join('inpiprotocolos dp', 'd.id = dp.id_despacho', 'left'); 
        $this->select("
                    cfg_s.id AS id_servico,
                    cfg_s.planilha,
                    inpiprocessos.processo,
                    inpiprocessos.id as id_processo,
                    cfg_sd.paint,
                    (SELECT GROUP_CONCAT(CONCAT(titular,' [',pais,'/',estado,']') SEPARATOR ' / ') FROM inpiprocessostitulares WHERE id_processo = inpiprocessos.id AND pais = 'BR' GROUP BY id_processo) AS titular,
                    d.textoComplementar as observacao,
                    dp.numero AS peticao,
                    dp.data,
                    dp.requerente,
                    dp.procurador
            ");
        // se exigir que não tenha procurador
        $this->where("IF(cfg_s.procurador = 0, inpiprocessos.procurador = '', 1=1)");
        // se exigir que ele não tenha procurador do processo
        $this->where("IF(cfg_s.procurador_processo = 0, dp.procurador = '', 1=1)");
        // se exigir código do servico
        $this->where("IF(cfg_s.codigo != '', dp.codigoServico = cfg_s.codigo, 1=1)");
        // se existir NICE para filtrar
        //$this->where("IF(cfg_s.id = 1, (SELECT id FROM inpinice i, cfg_nice ci WHERE ci.especificacao = i.especificacao AND i.id_processo = inpiprocessos.id LIMIT 1) = 0, 1=1)");

        //$this->where("cfg_s.id IN (1)");
        //$this->where("inpiprocessos.processo = '920164790'");

        $this->orderBy("cfg_s.planilha", "ASC");
        $this->orderBy("titular", "ASC");

        //die('<pre>'.$this->getCompiledSelect());  
        return $this->findAll();
    }

}