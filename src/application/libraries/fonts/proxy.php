<?php
declare(strict_types=1);

use Fonts\Types as Types;

class Proxy
{
  protected $_fonts = array();

  public function addFont(string $key, string $type, string $file): Proxy
  {

    $this->_fonts[$type] = $this->_fonts[$type] ?? $file : array();

    return $this;
  }

  public function addFontTypes(string $key, array $types): Proxy
  {
    foreach ($types as $type => $file)
    {
      $this->addFont($key, $type, $file);
    }
    return $this;
  }

  public function removeFont(string $key, string $type): Proxy
  {

    $this->_fonts[$type][$key] ?? unset($this->_fonts[$type][$key]) : null;

    return $this;
  }

  public function getFont(string $key, string $type): ?string
  {
    return $this->_fonts[$type][$key] ?? $this->_fonts[$type][$key] : null;

  }

  public function sniff(string $agent): ?array
  {
     $browser = "#(opera|ie|firefox|chrome|version)[\s\/:]([\w\d\.]+)?.*?(safari|version[\s\/:]([\w\d\.]+)|$)#i";
     $platform = "#(ipod|iphone|ipad|webos|android|win|mac|linux)#i";

    if (preg_match($browser, $agent, $browsers))
    {
      if (preg_match($platform, $agent, $platforms))
      {
        $platform = $platforms[1];
      }
      else
      {
        $platform = "other";
      }

      return array(
        "browser" => (strtolower($browsers[1]) == "version") ? strtolower($browsers[3]) : strtolower($browsers[1]),
        "version" => (float) (strtolower($browsers[1]) == "opera") ? strtolower($browsers[4]) : strtolower($browsers[2]),
        "platform" => strtolower($platform)
      );
    }

      return false;
  }

  public function detectSupport(string $agent): ?array
  {
    $sniff = $this->sniff($agent);

    if ($sniff)
    {
      switch ($sniff["platform"])
      {
          case "win":
          case "mac":
          case "linux":
          {
            switch ($sniff["browser"])
            {
              case "opera":
              {
                return ($sniff["version"] > 10) ? array(Types::TTF, Types::OTF, Types::SVG) : false;
              }
              case "safari":
              {
                return ($sniff["version"] > 3.1) ? array(Types::TTF, Types::OTF) : false;
              }
              case "chrome":
                {
                  return ($sniff["version"] > 4) ? array(Types::TTF, Types::OTF) : false;
                }
              case "firefox":
                {
                  return ($sniff["version"] > 3.5) ? array(Types::TTF, Types::OTF) : false;
                }
              case "ie":
                {
                  return ($sniff["version"] > 4) ? array(Types::EOT) : false;
                }
              }
            }
          }
      }

      return false;
  }

  public function serve(string $key, string $agent): array
  {
    $support = $this->detectSupport($agent);

    if ($support)
    {
      $fonts = array();

      foreach ($support as $type)
    	{
    	   $font = $this->getFont($key, $type);

          if ($font)
    			{
    		      $fonts[$type] = $this->getFont($key, $type);
    			}
    	}

      return $fonts;

    }

      return array();

  }
}
