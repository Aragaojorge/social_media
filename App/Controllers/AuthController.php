<?php

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    class AuthController extends Action {

        public function autenticar() {

            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));

            $usuario->autenticar();

            if($usuario->__get('id') != '' && $usuario->__get('nome')) {

                // echo 'Autenticado';
                session_start();

                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');

                // forçamos o redirecionamento para uma nova action chamada timeline que ficará dentro de um novo
                // controlador AppController.php
                header('Location: /timeline');

            } else {

                header('Location: /?login=erro');

            }

        }

        public function sair() {
            
            // o método sair é chamado dentro de timeline.phtml
            // a rota dele está em Route.php
            session_start();
            session_destroy();
            // ao destruir a sessão devemos retornar para a página inicial que é a rota raiz
            header('Location: /');

        }

    }

?>