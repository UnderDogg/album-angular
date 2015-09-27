<?php
/**
 * Acl.php
 * NDN\Plugins\Acl
 *
 * The Acl plugin
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-10-31
 * @category    Plugins
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN\Plugins;

use \Phalcon\Acl as PhAcl;
use \Phalcon\Acl\Role as PhRole;
use \Phalcon\Acl\Resource as PhResource;
use \Phalcon\Acl\Adapter\Memory as Memory;
use \Phalcon\Mvc\User\Plugin as Plugin;

use \Phalcon\Events\Event as PhEvent;
use \Phalcon\Mvc\Dispatcher as PhDispatcher;

class Acl extends Plugin
{

  /**
   * @var Phalcon\Acl\Adapter\Memory
   */
  protected $_acl;

  public function __construct($di) {
    $this->_dependencyInjector = $di;
  }

  public function getAcl() {
    if (!$this->_acl) {
      $acl = new Memory();

      $acl->setDefaultAction(PhAcl::DENY);

      // Register roles
      $roles = array(
        'administrators'  => new PhRole('Administrators'),
        'users'  => new PhRole('Users'),
        'guests' => new PhRole('Guests')
      );
      foreach ($roles as $role) {
        $acl->addRole($role);
      }

      // Private area resources
      $privateResources = array(
        'awards'   => array('add', 'edit', 'delete'),
        'players'  => array('add', 'edit', 'delete'),
        'episodes' => array('add', 'edit', 'delete'),
      );

      foreach ($privateResources as $resource => $actions) {
        $acl->addResource(new PhResource($resource), $actions);
      }

      // Public area resources
      $publicResources = array(
        'index'    => array('index'),
        'about'    => array('index'),
        'awards'   => array('index'),
        'players'  => array('index'),
        'episodes' => array('index'),
        'session'  => array('index', 'start'),
        'contact'  => array('index', 'send')
      );

      foreach ($publicResources as $resource => $actions) {
        $acl->addResource(new PhResource($resource), $actions);
      }

      //Grant access to public areas to both users and guests
      foreach ($roles as $role) {
        foreach ($publicResources as $resource => $actions) {
          $acl->allow($role->getName(), $resource, '*');
        }
      }

      // Grant access to private area to role Users
      foreach ($privateResources as $resource => $actions) {
        foreach ($actions as $action) {
          $acl->allow('Administrators', $resource, $action);
          $acl->allow('Users', $resource, $action);
        }
      }

      $this->_acl = $acl;
    }

    return $this->_acl;
  }

  /**
   * This action is executed before execute any action in the application
   */
  public function beforeDispatch(PhEvent $event, PhDispatcher $dispatcher) {

    $auth = $this->session->get('auth');
    if (!$auth) {
      $role = 'Guests';
    } else {
      $role = 'Users';
    }

    $controller = $dispatcher->getControllerName();
    $action = $dispatcher->getActionName();

    $acl = $this->getAcl();

    $allowed = $acl->isAllowed($role, $controller, $action);
    return true;
    /*
    if ($allowed != PhAcl::ALLOW) {
      $this->flash->error("On carefull consideration we have found that you do not have access");
      $dispatcher->forward(
        array(
          'controller' => 'index',
          'action'     => 'index'
        )
      );

      return false;
    }
    */

  }

}