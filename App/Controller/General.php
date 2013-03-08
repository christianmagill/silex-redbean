<?php

namespace App\Controller{

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


    class General
    {
        public function name(Request $request, Application $app, $name){
            $user = \R::findOne('user','email = ?',array('joe@email.com'));
            print_r($user);
            echo $user->name;

            return $app['twig']->render('home.twig', array('name' => $name));
        }

        public function home(Request $request, Application $app)
        {
            return $app['twig']->render('login.twig',array(
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username')
            ));
        }

        public function foo()
        {
            return "Foo Accessible!";
        }


    }

}