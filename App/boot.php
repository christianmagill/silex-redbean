<?php

define('APP_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

require_once BASE_DIR . DS . 'vendor' . DS . 'autoload.php';


$app = new  Silex\Application();

$app['debug'] = true;

// Setup RedBean ORM

class R extends RedBean_Facade{} // simple reference to facade

// use RedBean_Facade as R;

R::setup('mysql:host=localhost;dbname=silex','root','');

class MyModelFormatter implements RedBean_IModelFormatter{
    public function formatModel($model){
        return $model.'_Model';
    }
}

$formatter = new MyModelFormatter;
RedBean_ModelHelper::setModelFormatter($formatter);

R::debug(true);
R::freeze(false);

// Monolog Logger Service

$app->register(new Silex\Provider\MonologServiceProvider(),array(
'monolog.logfile' => BASE_DIR . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'app.log',
'monolog.level' => Monolog\Logger::DEBUG,
'monolog.name' => 'myapp'
));

// Session Service

$app->register(new Silex\Provider\SessionServiceProvider(),array(
'session.storage.save_path' => BASE_DIR . DIRECTORY_SEPARATOR . 'sessions'
));

$app->before(function ($request) {
    $request->getSession()->start();
});

// Security Service

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'foo' => array('pattern' => '^/foo'), // Example of an url available as anonymous user
        'default' => array(
            'pattern' => '^.*$',
            'anonymous' => true, // Needed as the login path is under the secured area
            'form' => array('login_path' => '/', 'check_path' => 'login_check'),
            'logout' => array('logout_path' => '/logout'), // url to call for logging out
            'users' => $app->share(function() use ($app) {
                // Specific class App\User\UserProvider is described below
                return new App\Model\UserProvider();
            }),
        ),
    ),
    'security.access_rules' => array(
        // You can rename ROLE_USER as you wish
        array('^/.+$', 'ROLE_BUILDER'),
        array('^/foo$', ''), // This url is available as anonymous user
    )
));


// Database Setup
R::nuke();
$roles = array('ROLE_ADMIN','ROLE_AGENT','ROLE_BUILDER','ROLE_ADVERTISER');

foreach ($roles as $role) {
    $r = \R::dispense('role');
    $r->name = $role;
    R::store($r);
}

$u = \R::dispense('user');
$u->username = 'joeuser';
$u->email = 'joe@email.com';
$u->sharedRole[] = R::findOne('role','name = ?',array('ROLE_BUILDER'));
$u->salt = md5(mcrypt_create_iv(22, MCRYPT_RAND));
$u->password = $app['security.encoder.digest']->encodePassword('mypassword',$u->salt);
\R::store($u);

$u2 = \R::findOne('user','username = ?',array('joeuser'));


// URL Generator Service

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Twig Templating Servicejo

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => APP_DIR . DIRECTORY_SEPARATOR . 'View'
));

// SwiftMail Service

$app->register(new Silex\Provider\SwiftmailerServiceProvider(),array(
'host' => 'host',
'port' => '25',
'username' => 'username',
'password' => 'password',
'encryption' => null,
'auth_mode' => null
));

// Routes

require_once 'Route/Admin.php';
require_once 'Route/Builder.php';
require_once 'Route/Member.php';
require_once 'Route/General.php';

$app->run();

