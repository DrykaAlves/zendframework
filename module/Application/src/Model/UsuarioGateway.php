<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

class UsuarioGateway
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function persistir(Usuario $model)
    {
       
        $model->getArrayCopy();

        $this->tableGateway->insert($model);
    }

    public function listar()
    {
        return $this->tableGateway->select();
    }

    public function visualizar(Usuario $model)
    {
        return $this->tableGateway->select(['id' => $model->id]);
 
    }

    public function excluir(Usuario $model)
    {
        $this->tableGateway->delete(['id' => $model->id]);
    }

    public function atualizar(Usuario $model)
    {
        $model->getArrayCopy();

        $this->tableGateway->update($dados, ['id' => $model->id]);
    }
    public function buscar($id)
    {
        $resultados = $this->tableGateway->select(['id' => $id]);
        
        return $resultados->current();
    }
}
