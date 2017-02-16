<?php
namespace Hkt\Psr7Asset;

/**
 *
 * Creates an asset entity.
 *
 */
class AssetService
{
    /**
     *
     * A locator to find the location
     *
     * @var AssetLocator $locator
     *
     */
    protected $locator;

    /**
     *
     * A map of filename extensions to media types.
     *
     * @var array
     *
     */
    protected $types = array(
        '.css'  => 'text/css',
        '.gif'  => 'image/gif',
        '.jpe'  => 'image/jpeg',
        '.jpeg' => 'image/jpeg',
        '.jpg'  => 'image/jpeg',
        '.js'   => 'text/javascript',
        '.json' => 'application/json',
        '.png'  => 'image/png',
    );

    /**
     *
     * Constructor.
     *
     * @param AssetLocator $locator A locator which can get the full path of asset from vendor/package/file
     *
     * @param array $types Overrides to the media type mappings.
     *
     */
    public function __construct(AssetLocator $locator, $types = array())
    {
        $this->locator = $locator;
        $this->types = array_merge($this->types, $types);
    }

    /**
     *
     * Returns the path and type of an asset.
     *
     * @param string $vendor The vendor name.
     *
     * @param string $package The package name.
     *
     * @param string $file The asset file within within the vendor package.
     *
     * @return \StdClass An object with properties $path (the real path to the
     * asset) and $type (the media type of the asset).
     *
     */
    public function getAsset($vendor, $package, $file)
    {
        $asset = (object) array(
            'path' => null,
            'type' => null,
        );
        $asset->path = $this->getPath($vendor, $package, $file);
        $asset->type = $this->getType($asset->path);
        return $asset;
    }

    /**
     *
     * Gets the real path to an asset.
     *
     * @param string $vendor The vendor name.
     *
     * @param string $package The package name.
     *
     * @param string $file The asset file within within the vendor package.
     *
     * @return string
     *
     */
    protected function getPath($vendor, $package, $file)
    {
        // check if path mapped to some other file
        // vendor/package/css/style.css => public/css/style.css
        $path = "{$vendor}/{$package}/{$file}";
        if ($this->locator->has($path)) {
            return realpath($this->locator->get($path));
        }
        $path = "{$vendor}/{$package}";
        if ($this->locator->has($path)) {
            $dir = rtrim($this->locator->get($path), DIRECTORY_SEPARATOR);
            return realpath($dir . DIRECTORY_SEPARATOR . $file);
        }
    }

    /**
     *
     * Gets the media type of an asset.
     *
     * @param string $path The real path to the asset.
     *
     * @return string
     *
     */
    protected function getType($path)
    {
        $ext = strrchr($path, '.');
        if (isset($this->types[$ext])) {
            return $this->types[$ext];
        }
    }
}
