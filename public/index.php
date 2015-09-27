<?php

use Phalcon\Http\Response;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Logger\Adapter\File as Logger;

error_reporting(E_ALL);

try {

  if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
  }

  // Using require once because I want to get the specific
  // bootloader class here. The loader will be initialized
  // in my bootstrap class
  require_once ROOT_PATH . '/app/library/NDN/Bootstrap.php';
  require_once ROOT_PATH . '/app/library/NDN/Error.php';

  $di = new \Phalcon\DI\FactoryDefault();
  $app = new \NDN\Bootstrap($di);

  echo $app->run(array());

} catch (\Phalcon\Exception $e) {

  /**
   * Log the exception
   */
  $logger = new Logger(APP_PATH . '/app/logs/error.log');
  $logger->error($e->getMessage());
  $logger->error($e->getTraceAsString());

  /**
   * Show an static error page
   */
  $response = new Response();
  $response->redirect('maintenance.html');
  $response->send();
} catch (PDOException $e) {

  /**
   * Log the exception
   */
  $logger = new Logger(APP_PATH . '/app/var/logs/dberror.log');
  $logger->error($e->getMessage());
  $logger->error($e->getTraceAsString());

  /**
   * Show an static error page
   */
  $response = new Response();
  $response->redirect('maintenince.html');
  $response->send();

} catch (Exception $e) {

  /**
   * Log the exception
   */
  $logger = new Logger(APP_PATH . '/app/logs/error.log');
  $logger->error($e->getMessage());
  $logger->error($e->getTraceAsString());

  /**
   * Show an static error page
   */
  $response = new Response();
  $response->redirect('505.html');
  $response->send();
}
