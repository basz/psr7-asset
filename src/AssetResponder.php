<?php
namespace Hkt\Psr7Asset;

use Interop\Http\Factory\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
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
     * @var Interop\Http\Factory\ResponseFactoryInterface
     *
     */
    protected $responseFactory;

    /**
     *
     * Constructor.
     *
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->data = (object) array();
        $this->responseFactory = $responseFactory;
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
    public function setData($data): void
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
    public function getData(): object
    {
        return $this->data;
    }

    /**
     *
     * Returns the response.
     *
     * @throws Exception\FileNotReadable
     *
     * @return ResponseInterface $response
     *
     */
    public function __invoke(): ResponseInterface
    {
        if ($this->isValidAsset()) {
            return $this->ok();
        }

        throw new Exception\FileNotReadable();
    }

    /**
    * Validate the asset
    *
    * @return bool
    */
    protected function isValidAsset(): bool
    {
       return isset($this->data->asset->path)
           && is_file($this->data->asset->path)
           && is_readable($this->data->asset->path);
    }

    /**
     * Sets a 200 OK response with the asset contents.
     *
     * @return ResponseInterface
     */
    protected function ok(): ResponseInterface
    {
        $path = $this->data->asset->path;
        $callable = function () use ($path) {
            $file = new SplFileObject($path);
            while (! $file->eof()) {
                echo $file->fgets();
            }

            return '';
        };

        $response = $this->responseFactory->createResponse(200);

        return $response
            ->withBody(new CallbackStream($callable))
            ->withHeader('Content-Length', (string) filesize($this->data->asset->path))
            ->withHeader('Content-Type', $this->data->asset->type)
        ;
    }
}
