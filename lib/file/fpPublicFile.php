<?php

class fpPublicFile extends fpFile
{
  public function __construct($path)
  {
    if (file_exists(sfConfig::get('sf_web_dir').'/'.$path)) {
      $path = sfConfig::get('sf_web_dir').'/'.$path;
    }
    
    return parent::__construct($path);
  }
  
  /**
   * @return string
   */
  public function getUrl($absolute = false)
  {
    if (strpos($this->getPath(), sfConfig::get('sf_web_dir')) === false) {      
      throw new fpFileException('The file `'.$this->getPath().'` is not a valid public file. The file should be in sub subdirectory of `'.sfConfig::get('sf_web_dir').'`');
    }
    
    $domain = '';
    if ($absolute)  {
      if (!isset($_SERVER['HTTP_HOST'])) {
        throw new Exception('The $_SERVER[\'HTTP_HOST\'] is not defined so I cannot guess what is the domain. Maybe you trying to use it from CLI?');
      }
      
      $domain = 'http://'.$_SERVER['HTTP_HOST'];
    }
    
    $relativeUrl = str_replace(sfConfig::get('sf_web_dir'), '', $this->getPath()); 
    
    return $domain . $relativeUrl;
  }
  
  /**
   * @return PublicFolder
   */
  public function getFolder()
  {
    return new fpPublicFolder(dirname($this->getPath()));
  }
}