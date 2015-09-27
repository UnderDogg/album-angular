<?php
/**
 * PlayersController.php
 * PlayersController
 *
 * The players controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-26
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Tag as Tag;
use \Phalcon\Mvc\View as View;

class PlayersController extends \NDN\Controller
{
  /**
   * Initializes the controller
   */
  public function initialize() {
    Tag::setTitle('Manage Players');
    parent::initialize();

    $this->_bc->add('Players', 'players');

    $auth = $this->session->get('auth');
    $add = '';

    if ($auth) {

      $add = Tag::linkTo(
        array(
          'players/add',
          'Add Player'
        )
      );
    }

    $this->view->setVar('addButton', $add);
    $this->view->setVar('menus', $this->constructMenu($this));
  }

  /**
   * index Action
   */
  public function indexAction() {

  }

  /**
   * get Action - gets data back
   */
  public function getAction() {
    $this->view->setRenderLevel(View::LEVEL_LAYOUT);

    // Invalidate the cache
    $results = $this->cache->get($this->getCacheHash('model'));

    if ($results === null) {

      $players = Players::find();

      if (count($players) >= 0) {
        foreach ($players as $player) {
          $results[] = array(
            'id'          => $player->id,
            'name'        => $player->name,
            'active'      => $player->active,
            'active_text' => $this->transformActive($player->active),
          );
        }
      }

      $results = json_encode(array('results' => $results));

      $this->cache->save($this->getCacheHash('model'), $results);
    }

    echo $results;
  }

  /**
   * add Action
   */
  public function addAction() {
    $auth = $this->session->get('auth');

    if ($auth) {

      if ($this->request->isPost()) {

        $player = new Players();
        $this->setPlayer($player, $auth);

        if (!$player->save()) {
          foreach ($player->getMessages() as $message) {
            $this->flash->error((string)$message);
          }
        } else {

          $this->view->disable();

          $this->flash->success('Player created successfully');

          // Invalidate the cache
          $this->cache->delete($this->getCacheHash('model'));

          $this->response->redirect('players/');
        }
      }
    }
  }

  /**
   * edit Action
   *
   * @param $id
   */
  public function editAction($id) {
    $auth = $this->session->get('auth');

    if ($auth) {

      $id = $this->filter->sanitize($id, array('int'));
      $player = Players::findFirst('id=' . $id);

      if (!$player) {
        $this->flash->error('Player not found');
        $this->response->redirect('players/');
      }

      if ($this->request->isPost()) {

        $this->setPlayer($player, $auth);

        if (!$player->save()) {
          foreach ($player->getMessages() as $message) {
            $this->flash->error((string)$message);
          }
        } else {
          $this->view->disable();

          $this->flash->success('Player updated successfully');

          // Invalidate the cache
          $this->cache->delete($this->getCacheHash('model'));
          $this->response->redirect('players/');
        }

      }

      $this->view->setVar('id', $player->id);

      Tag::displayTo('id', $player->id);
      Tag::displayTo('name', $player->name);
      Tag::displayTo('active', $player->active);
    }
  }

  public function deleteAction($id) {
    $auth = Session::get('auth');

    if ($auth) {
      $id = $this->filter->sanitize($id, array('int'));
      $player = Players::findFirst('id=' . $id);
      if (!$player) {
        Session::setFlash(
          'error',
          'Player not found',
          'alert alert-error'
        );

        $this->response->redirect('players/');
      }

      if (!$player->delete()) {
        foreach ($player->getMessages() as $message) {
          Session::setFlash(
            'error',
            (string)$message,
            'alert alert-error'
          );
        }
        $this->response->redirect('players/');
      } else {
        Session::setFlash(
          'success',
          'Episode deleted successfully',
          'alert alert-success'
        );

        $this->response->redirect('players/');
      }
    }
  }

  /**
   * Transforms the active flag to text meaningful to the user
   *
   * @param $active
   *
   * @return string
   */
  private function transformActive($active) {
    return ($active == 1) ? 'Active' : '';
  }

  /**
   * Sets the player record up
   *
   * @param $player
   * @param $auth
   */
  private function setPlayer($player, $auth) {
    $datetime = date('Y-m-d H:i:s');

    $player->id = $this->request->getPost('id', 'int');
    $player->name = $this->request->getPost('name');
    $player->active = $this->request->getPost('active', 'int');

    $player->lastUpdate = $datetime;
    $player->lastUpdateUserId = (int)$auth['id'];

    $player->name = strip_tags($player->name);
    $player->name = addslashes($player->name);
  }
}