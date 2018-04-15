<?php
/**
 * Created by PhpStorm.
 * User: toha
 * Date: 4/15/18
 * Time: 7:32 AM
 */

require('./vendor/autoload.php');
require('config.php');

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

$app->get('/', function() use($app) {
    return "Hello world";
});

$app->post('/bot', function() use($app) {
    $data = json_decode(file_get_contents('php://input'));
    if (!$data)
        return "ne OK)";

    if ((!$data->secret !== VK_SECRET_TOKEN) && ($data->type !== 'confirmation'))
      return "ne OK)";

 switch ( $data->type)
 {
     //тип подтверждения
     case 'confirmation':
         return( VK_CONFIRMATION_CODE);
         break;

     //тип прихода нового сообщения
     case 'message_new':

         $request_params = array(
             'user_id' => $data->object->user_id,
             'message' => 'Тест',
             'access_token' => VK_TOKEN,
             'v'=>'5.74',
         );
     file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query(
             $request_params));
     return "OK";
  break;
 }
  return "ne OK)";
});

$app->run();