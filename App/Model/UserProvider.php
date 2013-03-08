<?php


namespace App\Model{

    use Symfony\Component\Security\Core\User\UserProviderInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\User;
    use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
    use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

    class UserProvider implements UserProviderInterface
    {

        /*
         *
        private $conn;

        public function __construct(Connection $conn)
        {
            $this->conn = $conn;
        }

        */

        public function loadUserByUsername($username){
            $user = \R::findOne('user','username = ?', array(strtolower($username)));
            if(!$user){
                throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            }
            return new UserModel($user);
        }

        public function refreshUser(UserInterface $user){
            return $this->loadUserByUsername($user->getUsername());
        }

        public function supportsClass($class)
        {
            return $class === 'UserModel';
        }
    }

}