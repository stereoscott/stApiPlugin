<?php
/**
 * Base stSoapClient class.
 *
 * @package stApi
 * @author Scott Meves
 */
abstract class BasestSoapClient extends SoapClient
{
  protected 
    $logger;
    
  public function getLogger() 
  {
    return $this->logger;
  }

  public function setLogger($logger) 
  {
    $this->logger = $logger;
  }

  public function __doRequest($request, $location, $action, $version, $one_way = 0) 
  { 
    try 
    {
      $response = parent::__doRequest($request, $location, $action, $version, $one_way);
    } 
    catch (Exception $e) 
    {
      if ($this->logger) 
      {
        $this->logger->logFullRequest($action, $request, '', array('error' => true));
      }
      throw $e;
    }
    
    if ($this->logger) 
    {
      $this->logger->logFullRequest($action, $request, $response);
    }
    
    return $response;
  }

}