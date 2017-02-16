<?php
namespace Hkt\Psr7Asset;

/**
 *
 * @package psr7-asset
 *
 */
class AssetLocator
{
    /**
     *
     * A map of vendor/package names to web asset directories.
     *
     * @var array $map
     *
     */
    protected $map = array();

    /**
     *
     * Constructor.
     *
     * @param array $map An array of key-value pairs where the key is the
     * path or vendor name and the value is full path or directory location.
     *
     */
    public function __construct(array $map = array())
    {
        $this->map = $map;
    }

    /**
     *
     * Set key / value
     *
     * @param string $path path or vendor/package
     *
     * @param string $location Full path or Directory location
     *
     * @return null
     *
     */
    public function set($path, $location)
    {
        $this->map[$path] = $location;
    }

    /**
     *
     * Does path exists
     *
     * @param string $path path or vendor/package
     *
     * @return bool
     *
     */
    public function has($path)
    {
        return isset($this->map[$path]);
    }

    /**
     *
     * Returns a helper object instance, using the map to factory it if needed.
     *
     * @param string $path path or vendor/package
     *
     * @throws Exception\PathNotFound
     *
     * @return string
     *
     */
    public function get($path)
    {
        if (! $this->has($path)) {
            throw new Exception\PathNotFound($path);
        }

        return $this->map[$path];
    }
}
