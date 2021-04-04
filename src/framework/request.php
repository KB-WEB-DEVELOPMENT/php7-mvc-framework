<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\Events as Events;
use Framework\StringMethods as StringMethods;
use Framework\RequestMethods as RequestMethods;
use Framework\Request\Exception as Exception;

class Request extends Base
{
  protected $_request;

  /**
  * @readwrite
  */
  public bool $_willFollow = true;

  /**
  * @readwrite
  */
  protected bool $_willShareSession = true;

  /**
  * @readwrite
  */
  protected array $_headers;

  /**
  * @readwrite
  */
  protected array $_options;

  /**
  * @readwrite
  */
  protected string $_referer;

  /**
  * @readwrite
  */
  protected string $_agent;

  protected function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} not implemented");
  }

  public function __construct(array $options)
  {
    parent::__construct($options);
    $this->agent = RequestMethods::server("HTTP_USER_AGENT", "Curl/PHP ".PHP_VERSION);
  }

  public function delete(string $url,array $parameters): object
  {
    return $this->request("DELETE", $url, $parameters);
  }

  function get(string $url,array $parameters): object
  {
    if (!empty($parameters))
    {
      $url .= StringMethods::indexOf($url, "?") ? "&" : "?";
      $url .= is_string($parameters) ? $parameters : http_build_query($parameters, "", "&");
    }
    return $this->request("GET", $url);
  }

  function head(string $url,array $parameters): object
  {
    return $this->request("HEAD", $url, $parameters);
  }

  function post(string $url, array $parameters): object
  {
    return $this->request("POST", $url, $parameters);
  }

  function put(string $url, array $parameters): object
  {
    return $this->request("PUT", $url, $parameters);
  }

  function request(string $method,string  $url): object
  {
    session_write_close();

    Events::fire("framework.request.request.before", array($method, $url, $parameters));

    $request = $this->_request = curl_init();

    if (is_array($parameters))
    {
      $parameters = http_build_query($parameters, "", "&");
    }

    $this
      ->_setRequestMethod($method)
      ->_setRequestOptions($url, $parameters)
      ->_setRequestHeaders();

    $response = curl_exec($request);

    if (!headers_sent())
    {
      session_start();
    }

    if ($response)
    {
      $response = new Request\Response(array(
          "response" => $response
      ));
    }
    else
    {
      throw new Exception\Response(ucfirst(curl_error($request)));
    }

    Events::fire("framework.request.request.after", array($method, $url, $parameters, $response));

    curl_close($request);
    return $response;
  }

  protected function _setOption(?string $key, ?string $value): Request
  {
    curl_setopt($this->_request, $key, $value);
    return $this;
  }

  protected function _normalize(?string $key): string
  {
    return "CURLOPT_".str_replace("CURLOPT_", "", strtoupper($key));
  }

  protected function _setRequestMethod(?string $method): Request
  {
    switch (strtoupper($method))
    {
      case "HEAD":
        $this->_setOption(CURLOPT_NOBODY, true);
        break;
      case "GET":
        $this->_setOption(CURLOPT_HTTPGET, true);
        break;
      case "POST":
        $this->_setOption(CURLOPT_POST, true);
        break;
      default:
        $this->_setOption(CURLOPT_CUSTOMREQUEST, $method);
        break;
    }

    return $this;
  }

  protected function _setRequestOptions(string $url, array $parameters): Request
  {
    $this
      ->_setOption(CURLOPT_URL, $url)
      ->_setOption(CURLOPT_HEADER, true)
      ->_setOption(CURLOPT_RETURNTRANSFER, true)
      ->_setOption(CURLOPT_USERAGENT, $this->agent);

    if (!empty($parameters))
    {
      $this->_setOption(CURLOPT_POSTFIELDS, $parameters);
    }

    if ($this->willFollow)
    {
      $this->_setOption(CURLOPT_FOLLOWLOCATION, true);
    }

    if ($this->willShareSession)
    {
      $this->_setOption(CURLOPT_COOKIE, session_name() . "=" . session_id());
    }

    if ($this->referer)
    {
      $this->_setOption(CURLOPT_REFERER, $this->referer);
    }

    foreach ($this->_options as $key => $value)
    {
      $this->_setOption(constant($this->_normalize($key)), $value);
    }

    return $this;
  }

  protected function _setRequestHeaders(): Request
  {
    $headers = array();

    foreach ($this->headers as $key => $value)
    {
      $headers[] = $key.': '.$value;
    }

    $this->_setOption(CURLOPT_HTTPHEADER, $headers);
    return $this;
   }
}
