<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController {
    
	private $table;

    public function __construct($gateway)
    {
        $this->table = $gateway;
    }

	
	public function indexAction() 
	{
        return new ViewModel(['email' => 'adriana.lfalves@gmail.com']);
    }

	// funcao segue padrao de nomeacao module+Action
	public function visualizarAction() 
	{
		// recebendo dado externo
		$id = $this->params()->fromRoute('id',0);

		$dado = $this->table->buscar($id);

		if ($id == 0) {
			return new ViewModel ([
				'dados' => $this->table->listar(),
			]);
		} else {
			return new ViewModel ([
				'dados' => $this->table->visualizar($dado),
			]);
		}

		// nao é necessario ter um return
		return new ViewModel([
			'numero' => $id,
		]);
	}
	
	// funcao segue padrao de nomeacao module+Action
	public function cadastrarAction() 
	{
		// guardar requisicao
		$req = $this->getRequest();
		
		// verificar se tem informacao vindo por post
		if ($req->isPost()) {
			$dados = $req->getPost();
			

            $model = new \Application\Model\Usuario();
            $model->exchangeArray(['email' => $dados['email'], 'senha' => $dados['senha']]);

            $this->table->persistir($model);
        }

		return new ViewModel ([
			'teste' => isset($dados['email']) ? $dados['email']:'',
			// 'teste' => isset($_POST['email']) ? $_POST['email']:'', // tbm funciona, mas nao devemos usar
		]);
	}

	public function excluirAction() {

		$id = $this->params()->fromRoute('id');
		$dado = $this->table->buscar($id);

		$this->table->excluir($dado);

		return $this->redirect()->toRoute('usuario_perfil');

	}

	public function atualizarAction() {
        $req = $this->getRequest();
        $id = $this->params()->fromRoute('id');
		$form = new \Application\Form\UsuarioForm();
		
		$model = new \Application\Model\Usuario();
		$model->exchangeArray($form->getData());

        if ($req->isPost()) {
            $dados = $req->getPost();

            $form->setData($dados);

            if (!$form->isValid()) {
            }

            $this->table->atualizar($model);
        } else {
            $dados = $this->table->visualizar($model);
            $form->bind($dados);
        }

        return new ViewModel([
            'form' => $form,
        ]);


    }

}