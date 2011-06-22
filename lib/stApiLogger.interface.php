<?php

interface stApiLoggerInterface
{
  public function log($request, $location, $action, $version = '', $suffix = null);
  
  public function logFullRequest($method, $request, $response, $options = array());
}