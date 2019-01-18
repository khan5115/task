<?php

namespace Drupal\common\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Drupal\Component\Serialization\Json;

class CommonController extends ControllerBase
{
  public function nodeContent(Request $request)
  {
    $path = \Drupal::request()->getpathInfo();
    $arg  = explode('/',$path);
    $length = count($arg);
    $arg_key = $arg[$length - 2];
    $nid = $arg[$length - 1];
    $site_api_key =  \Drupal::config('common.settings')->get('siteapikey');
    $node_exist = \Drupal::entityQuery('node')->condition('nid', $nid)->condition('type', 'page')->execute();
    if ($site_api_key == $arg_key  && !empty($node_exist))
    {
      $node = \Drupal\node\Entity\Node::load($nid);
      $title = $node->getTitle();
      $body = $node->get('body')->getValue();
      $data = array('title'=> $title, 'body'=> $body);
    }
    else {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
   return new JsonResponse( $data );
  }
}
