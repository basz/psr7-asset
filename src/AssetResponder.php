<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\CallbackStream;
use SplFileObject;

/**
 *
 * Builds an asset response.
 *
 */
class AssetResponder
{
    /**
     *
     * Data for modifying the response.
     *
     * @var object
     *
     */
    protected $data;

    /**
     *
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->data = (object) array();
    }

    /**
     *
     * Sets data for modifying the response.
     *
     * @param mixed $data Data for modifying the response; will be cast to an
     * object.
     *
     * @return null
     *
     */
    public function setData($data)
    {
        $this->data = (object) $data;
    }

    /**
     *
     * Gets data for modifying the response.
     *
     * @return object
     *
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * Modifies and returns the response.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface $response
     *
     */
    public function __invoke(ResponseInterface $response)
    {
        if ($this->isValidAsset()) {
            return $this->ok($response);
        }
        return $this->notFound($response);
    }

    /**
    * Validate the asset
    *
    * @return bool
    */
    protected function isValidAsset()
    {
       return isset($this->data->asset->path)
           && is_file($this->data->asset->path)
           && is_readable($this->data->asset->path);
    }

    /**
     * Sets a 200 OK response with the asset contents.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    protected function ok(ResponseInterface $response)
    {
        $path = $this->data->asset->path;
        $callable = function () use ($path) {
            $file = new SplFileObject($path);
            while (! $file->eof()) {
                echo $file->fgets();
            }

            return '';
        };
        return $response
            ->withStatus(200)
            ->withBody(new CallbackStream($callable))
            ->withHeader('Content-Length', (string) filesize($this->data->asset->path))
            ->withHeader('Content-Type', $this->data->asset->type)
        ;
    }

    /**
     * Sets a 404 Not Found response.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    protected function notFound(ResponseInterface $response)
    {
        $response->getBody()->write("Not found");
        return $response->withStatus(404);
    }
}
