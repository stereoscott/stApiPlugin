<?php

/**
 * Base stApi class
 *
 * @package stApi
 * @author Scott Meves
 */
interface stApiInterface
{
  protected static function initCacheManager($cacheDir);
  
  protected static function getCacheNamespace();
  
  /**
   * Sends a Upcoming API call. Returns false if the request does not return a valid response.
   *
   * @param string $command
   * @param array $params urlencoded and sent in the query string 
   * @return Object $responseObject
   */
  public function sendRequest($method, $params = array());
    
  public function getRequestStatus();

  public function isSuccessful();
    
  /**
   * Sets the url used with an API request
   *
   * @param string $v
   * @return void
   */
  protected function setUrl($v);
    
  /**
   * Returns the url used for the request
   *
   * @return string $responseText
   */  
  public function getUrl();
  
  /**
   * Returns $responseText which contains the full result text from the last api call.
   *
   * @return string $responseText
   */  
  public function getResponseText();
  
  /**
   * Sets $responseText. Should be used to store the full result text from the last api call.
   *
   * @param string $str 
   * @return void
   */
  protected function setResponseText($text, $url = null);

  /**
   * Returns $responseXml which contains the xml object from the last api call.
   *
   * @return SimpleXMLElement $responseXml
   */  
  public function getResponseXML();    
  /**
   * Sets $responseXml with xml.
   *
   * @param SimpleXMLElement $responseXml
   * @return void
   */
  protected function setResponseXml($xml);
  
  /**
   * Logs the last response string into the file specified.
   *
   * @return void
   **/
  public function logLastResponse($method = null, $url = array());
  
  public static function getCacheManager();
    
  public function loadFromCache($fileName);

}