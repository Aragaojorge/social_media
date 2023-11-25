<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index');
	}

	public function inscreverse() {
		// colocado aqui para evitar erros gerados pelo else
		$this->view->usuario = array(
				'nome' => '',
				'email' => '',
				'senha' => '',
			);

		$this->view->erroCadastro = false;

		$this->render('inscreverse');
	}

	public function registrar() {

		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		// md5 Calculate the md5 hash of a string
		$usuario->__set('senha', md5($_POST['senha']));

		
		if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {
		
				$usuario->salvar();

				$this->render('cadastro');

		} else {
			// vai retornar os campos do formulário preenchidos para o user poder checar 
			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha'],
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse');
		}

	}

}


?>