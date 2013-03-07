<?php

namespace App\Model{

    use Symfony\Component\Security\Core\User\UserInterface;

    class User_Model extends RedBean_SimpleModel implements UserInterface{

        function getRoles(){

            $roles = $this->sharedRole;
            $r = array();
            foreach ($roles as $role) {
                $r[] = $role->name;
            }
            return $r;
        }

        function getPassword(){
            return $this->password;
        }

        function getSalt(){
            return $this->salt;
        }

        function getUsername(){
            return $this->username;
        }

        function eraseCredentials(){

        }

    }

}