<?php
class BasestApiLogger implements stApiLoggerInterface
{
  protected 
    $logDir;

  // wrapping the SoapClient instance with the decorator
  public function __construct($logDir = null)
  {
    if (null === $logDir) {
      $logDir = sfConfig::get('sf_log_dir');
    }
    
    $this->logDir = $logDir;
  }
  
  public function getLogDir() {
    return $this->logDir;
  }

  public function setLogDir($v) {
    $this->logDir = $v;
  }
  
  public function log($request, $location, $action, $version = '', $suffix = null)
  {
    if (sfConfig::get('sf_logging_enabled')) {
      $this->initLogDir();

      $logString = ""; //"<!--\n$action\n$location\n$version\n-->\n";
      
      $actionPath = explode('/', $action);
      if ($actionPath) {
        $suffix = '_'.end($actionPath).$suffix;
      }
      
      $fileName = time().$suffix.'.txt';

      file_put_contents($this->logDir.'/'.$fileName, $logString . $request);
    }
  }
  
  private function initLogDir()
  {
    if (!file_exists($this->logDir)) {
      $oldumask = umask(0);
      $result = @mkdir($this->logDir, 0777);
      umask($oldumask);
      if (!$result) {
        sfContext::getInstance()->getLogger()->err('Could not save to log file '.$this->logDir);
        return;
      }
    } 
  }
  
  public function logFullRequest($method, $request, $response, $options = array())
  {
    if (sfConfig::get('sf_logging_enabled')) {
      $this->initLogDir();
      
      $actionPath = explode('/', $method);
      if ($actionPath) {
        $method = end($actionPath);
      }
      
      $suffix = isset($options['suffix']) ? $suffix : '';
      
      $fileName = time().'_'.$method.'_request'.$suffix'.txt';

      file_put_contents($this->logDir.'/'.$fileName, $request);

      $fileName = time().'_'.$method.'_response'.$suffix'.txt';

      file_put_contents($this->logDir.'/'.$fileName, $response);
    }
  }
}