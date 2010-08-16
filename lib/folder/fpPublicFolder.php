<?php

class fpPublicFolder extends fpFolder
{
  public function __construct($path = null)
  {
    $resolvedPath = false;
    $resolvedPath || $resolvedPath = $this->_resolveAsDefault($path);
    $resolvedPath || $resolvedPath = $this->_resolveAsAbsolutePath($path);
    $resolvedPath || $resolvedPath = $this->_resolveAsRelativeAppWebDirPath($path);

    if (!$resolvedPath) {
      throw new fpFileException('The path `'.$path.'` is not a valid public folder. The folder should be sub a subdirectory of `'.$this->_getSfWebDir().'`');
    }

    return parent::__construct($resolvedPath);
  }
  
  /**
   * @return string
   */
  public function getUrl()
  {
    return str_replace($this->_getSfWebDir(), '', $this->_path);
  }
  
  /**
   * @string
   */
  protected function _getAppWebDir()
  {
    if (!$path = sfConfig::get('app_web_dir')) {
      throw new fpFileException('a parameter `app_web_dir` should be defined in app.yml');
    }
    if (strpos($path, $this->_getSfWebDir()) === false) {
      throw new fpFileException('a `app_web_dir` ('.$path.') dir should be a sub folder of `sf_web_dir` ('.$this->_getSfWebDir().')');
    }
    
    return $path;
  }
  
  protected function _getSfWebDir()
  {
    return sfConfig::get('sf_web_dir');
  }
  
  protected function _resolveAsDefault($path)
  {
    return is_null($path) ? $this->_getAppWebDir() : false;
  }
  
  protected function _resolveAsAbsolutePath($path)
  {
    return strpos($path, $this->_getSfWebDir()) !== false ? $path : false;
  }
  
  protected function _resolveAsRelativeAppWebDirPath($path)
  {
    return $this->_resolveAsAbsolutePath("{$this->_getAppWebDir()}/{$path}");
  }
}