<?php
/**
 * AwardsController.php
 * AwardsController
 *
 * The awards controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-24
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Tag as Tag;
use \Phalcon\Mvc\View as View;

class AwardsController extends \NDN\Controller
{
  /**
   * Initializes the controller
   */
  public function initialize() {
    Tag::setTitle('Manage Awards');
    parent::initialize();

    $this->_bc->add('Awards', 'awards');

    $auth = $this->session->get('auth');
    $add = '';

    if ($auth) {
      $add = Tag::linkTo(
        array(
          'awards/add',
          'Add Award'
        )
      );
    }

    $this->view->setVar('addButton', $add);
    $this->view->setVar('menus', $this->constructMenu($this));
  }

  /**
   * The index action
   */
  public function indexAction() {
  }

  /**
   * The add action
   */
  public function addAction() {
    $auth = $this->session->get('auth');

    if ($auth) {
      $this->_bc->add('Add', 'awards/add');

      $allEpisodes = Episodes::find(array('order' => 'air_date DESC'));
      $allUsers = Users::find(array('order' => 'username'));
      $allPlayers = Players::find(array('order' => 'active DESC, name'));

      $this->view->setVar('users', $allUsers);
      $this->view->setVar('awards', $allEpisodes);
      $this->view->setVar('players', $allPlayers);

      if ($this->request->isPost()) {

        $datetime = date('Y-m-d H:i:s');

        $award = new Awards();
        $award->userId = $this->request->getPost('user_id', 'int');
        $award->awardId = $this->request->getPost('award_id', 'int');
        $award->playerId = $this->request->getPost('player_id', 'int');
        $award->award = $this->request->getPost('award', 'int');

        if (!$award->save()) {
          foreach ($award->getMessages() as $message) {
            $this->flash->error((string)$message);
          }
        } else {
          $this->view->disable();

          $this->flash->success('Award created successfully');
          $this->invalidateCache();
          $this->response->redirect('awards/');
        }

      }
    }
  }

  /**
   * Gets the Hall of Fame
   */
  public function getAction($action = 0, $limit = null) {
    $this->view->setRenderLevel(View::LEVEL_LAYOUT);

    $data = '';
    $results = $this->cache->get($this->getCacheHash('model'));

    if (!$results) {

      $awards = Awards::find();

      if (count($awards) > 0) {
        foreach ($awards as $award) {
          $data[] = array(
            'id'       => $award->id,
            'total'   => (int) $award->total,
            'name'    => $award->name,
            'percent' => (int) ($award->total * 100 / 100),
          );
        }
      }

      $results = json_encode(array('results' => $awards));

      //$results = json_encode(array('results' => $data));

      $this->cache->save($this->getCacheHash('model'), $results);
    }

    echo $results;
  }

  /**
   * cache invalidator
   */
  private function invalidateCache() {
    $cacheDir = $this->config->models->cache->cacheDir;
    $name = strtolower($this->getName());

    foreach (glob($cacheDir . '*' . $name) as $filename) {

      // Remove the path $cacheDir and 'model.'
      $entry = str_replace($cacheDir, '', $filename);

      // $entry has the cache key
      $this->cache->delete($entry);
    }
  }
}
