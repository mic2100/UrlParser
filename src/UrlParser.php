<?php

namespace Mic2100\UrlParser;

class UrlParser
{
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var array
	 */
	private $urlParts;

	/**
	 * @var string
	 */
	private $scheme;

	/**
	 * @var string
	 */
	private $host;

	/**
	 * @var string
	 */
	private $port;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $pass;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $query;

	/**
	 * @var string
	 */
	private $fragment;

	/**
	 * @param string|null $url
	 */
	public function __construct($url = null)
	{
		$url !== null && $this->setUrl($url);
	}

	public function __toString()
	{
		return $this->buildUrl();
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @param string $url - must be a valid URL e.g. http://www.domain.com/path/file.html
	 * @return $this
	 * @throws \InvalidArgumentException - if the URL is not a valid string
	 */
	public function setUrl($url)
	{
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException(
				'Unable to set the URL it is not a valid URL, given: ' . var_export($url, true)
			);
		}

		$this->url = $url;

		$this->parseUrl();

		return $this;
	}

	/**
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @param string $scheme
	 * @return UrlParser
	 */
	public function setScheme($scheme)
	{
		$this->scheme = $scheme;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 * @return UrlParser
	 */
	public function setHost($host)
	{
		$this->host = $host;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param string $port
	 * @return UrlParser
	 */
	public function setPort($port)
	{
		$this->port = $port;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param string $user
	 * @return UrlParser
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPass()
	{
		return $this->pass;
	}

	/**
	 * @param string $pass
	 * @return UrlParser
	 */
	public function setPass($pass)
	{
		$this->pass = $pass;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return UrlParser
	 */
	public function setPath($path)
	{
		$this->path = $path;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param string $query
	 * @return UrlParser
	 */
	public function setQuery($query)
	{
		$this->query = $query;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * @param string $fragment
	 * @return UrlParser
	 */
	public function setFragment($fragment)
	{
		$this->fragment = $fragment;

		return $this;
	}

	/**
	 * Clears all the values that have been set
	 */
	public function clear()
	{
		$this->url = null;
		$this->urlParts = null;
		$this->scheme = null;
		$this->host = null;
		$this->port = null;
		$this->user = null;
		$this->pass = null;
		$this->path = null;
		$this->query = null;
		$this->fragment = null;
	}

	/**
	 * Parses the URL using PHP's parse_url() function.
	 * This is only called from the setUrl() method
	 */
	private function parseUrl()
	{
		$urlParts = parse_url($this->url);

		if (empty($urlParts)) {
			throw new \BadMethodCallException(
				'Unable to parse the supplied URL, given: ' . var_export($this->url, true)
			);
		}

		foreach ($urlParts as $segment => $urlPart) {
			$this->$segment = $urlPart;
		}
	}

	/**
	 * @return string
	 * @throws \BadMethodCallException - if no URL has been created usually because no properties have been set
	 */
	private function buildUrl()
	{
		$url = '';

		$this->scheme && $url .= $this->scheme . '://';

		if ($this->user) {
			$url .= $this->user;
			if ($this->pass) {
				$url .= ':' . $this->pass;
			}
			$url .= '@';
		}

		$this->host && $url .= $this->host;
		$this->port && $url .= ':' . $this->port;
		$this->path && $url .= '/' . ltrim($this->path, '/.');
		$this->query && $url .= '?' . $this->query;
		$this->fragment && $url .= '#' . $this->fragment;

		if (empty($url)) {
			throw new \BadMethodCallException('Unable to build URL');
		}

		return $url;
	}
}