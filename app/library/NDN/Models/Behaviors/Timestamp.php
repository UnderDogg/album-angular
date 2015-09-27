<?php
/**
 * Timestamp.php
 * NDN\Models\Behaviors\Timestamp
 *
 * Adds timestamp behavior in a model
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-10-26
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN\Models\Behaviors;

use \Phalcon\Mvc\Model\MetaData;
use \Phalcon\Db\Column;

class Timestamp
{
    protected $session;

    public function __construct($session)
    {
        $this->session= $session;
    }

    /**
     * beforeSave hook - called prior to any Save (insert/update)
     */
    public function beforeSave($record)
    {
        $auth     = $this->session->get('auth');
        $userId   = (isset($auth['id'])) ? (int) $auth['id'] : 0;
        $datetime = date('Y-m-d H:i:s');
        if (empty($record->created_at_user_id)) {
            $record->created_at         = $datetime;
            $record->created_at_user_id = $userId;
        }
        $record->last_update         = $datetime;
        $record->last_update_user_id = $userId;
    }
}