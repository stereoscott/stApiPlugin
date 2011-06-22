<?php
/**
 * Base stApi class.
 * Abstract class that handles a cacheManager which will store the results 
 *
 * @package stApi
 * @author Scott Meves
 */
abstract class BasestApi
{
  protected 
    $url                 = null,
    $responseText        = null,
    $responseXml         = null,
    $logDir              = null;
    
  protected static $cacheManager;
  
  /**
   * Sends an API call. Returns false if the request does not return a valid response.
   *
   * @param string $command
   * @param array $params urlencoded and sent in the query string 
   * @return Object $responseObject
   */
  abstract public function sendRequest($method, $params = array());
  
  /**
   * Returns the status string from the last request.
   *
   * @return string $status
   */
  abstract public function getRequestStatus();

  abstract public function isSuccessful();
  
  /**
   * Constructor.
   *
   * @param string $ywsid 
   * @return void
   */
  public function __construct($cacheNamespace = null)
  {
    self::initCacheManager(sfConfig::get('sf_cache_dir').'/api'.($cacheNamespace ? '/'.$cacheNamespace : ''));  
  }

  protected static function initCacheManager($cacheDir)
  {
    $cacheManager = new sfFileCache(array('cache_dir'=>$cacheDir));
    self::$cacheManager = $cacheManager;
  }

    
  /**
   * Returns the web services id as set in the object constructor.
   *
   * @return string $apiKey
   */
  protected function getApiKey()
  {
    return $this->apiKey;
  }

    
  /**
   * Sets the  web services API key for use in the API request.
   *
   * @param string $v
   * @return void
   */
  protected function setApiKey($v)
  {
    $this->apiKey = $v;
  }
  
  /**
   * Sets the web services url for use in the API request.
   *
   * @param string $v
   * @return void
   */
  protected function setUrl($v)
  {
    /*
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getLogger()->debug('{API}'.$v);
    }
    */
    
    $this->url = $v;
  }
  
  /**
   * Returns the url used with the API
   *
   * @return string $url
   */  
  public function getUrl() 
  {
    return $this->url;
  }
  
  
  /**
   * Returns $responseText which contains the full result text from the last api call.
   *
   * @return string $responseText
   */  
  public function getResponseText() 
  {
    return $this->responseText;
  }

  /**
   * Sets $responseText. Should be used to store the full result text from the last api call.
   *
   * @param string $str 
   * @return void
   */
  protected function setResponseText($text, $url = null) 
  {
    $this->responseText = $text;
    if ($url) {
      self::getCacheManager()->set($url, null, $text);
    }
  }
    

  /**
   * Returns $responseXml which contains the xml object from the last api call.
   *
   * @return SimpleXMLElement $responseXml
   */  
  public function getResponseXml()
  {
    if(!$this->responseXml && $this->getResponseText())
    {
      $this->setResponseXml(@simplexml_load_string($this->getResponseText()));
    }

    return $this->responseXml;
  }
    
  /**
   * Sets $responseXml with xml.
   *
   * @param SimpleXMLElement $responseXml
   * @return void
   */
  protected function setResponseXml($xml)
  {
    $this->responseXml = $xml;
  }
  
  /**
   * Logs the last response string into the file specified.
   *
   * @return void
   **/
  public function logLastResponse($method = null, $url = null)
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
    
    $logString = '<!-- '.$method."\n".$url."\n -->\n";
    
    $fileName = time().'.txt';
    
    file_put_contents($this->logDir.'/'.$fileName, $logString.$this->getResponseText());
  }

  public static function getCacheManager()
  {
    return self::$cacheManager;
  }
  
  public function loadFromCache($fileName)
  {
    throw new sfException('Need to implement this method');
    
    $str = file_get_contents($this->logDir.'/'.$fileName);
    $this->setResponseObject($this->decode($str)); // will only work if the current instance has the right output type set
    
    return $this->getResponseObject();
  }

}