<?php

namespace App\Model{

    use Symfony\Component\Security\Core\User\UserInterface;

    class UserModel implements UserInterface{

        private $bean = null;

        function __construct($bean){
            $this->bean = $bean;
        }

        function __get($prop){
            return $this->bean->$prop;
        }

        function __set($prop, $val){
            $this->bean->$prop = $val;
        }

        function __isset($prop){
            return isset($this->bean->$prop);
        }

        function __unset($prop){
            unset($this->bean->$prop);
        }

        function __call($method, $args){
            return call_user_func_array(array($this->bean,$method),$args);
        }

        function store(){
            \R::store($this->bean);
        }

        function trash(){
            \R::trash($this->bean);
        }

        function getRoles(){
            $roles = $this->bean->sharedRole;
            $r = array();
            foreach($roles as $role){
                $r[] = $role->name;
            }
            return $r;
        }

        function getPassword(){
            return $this->bean->password;
        }

        function getSalt(){
            return $this->bean->salt;
        }

        function getUsername(){
            return $this->bean->username;
        }

        function eraseCredentials(){

        }
    }
}