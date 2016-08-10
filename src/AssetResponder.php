<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;

/**
 *
 * Builds an asset response.
 *
 */
class AssetResponder
{
    /**
     *
     * A web response object.
     *
     * @var Response
     *
     */
    protected $response;

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
     * @return Response $response
     *
     */
    public function __invoke(ResponseInterface $response)
    {
        $this->response = $response;

        if (
            isset($this->data->asset->path) &&
            is_file($this->data->asset->path) &&
            is_readable($this->data->asset->path)
        ) {
            $this->ok(
                $this->data->asset->path,
                $this->data->asset->type
            );
        } else {
            $this->notFound();
        }
        return $this->response;
    }

    /**
     *
     * Sets a 200 OK response with the asset contents.
     *
     * @param string $path The filesystem path to the asset.
     *
     * @param string $type The asset media type.
     *
     * @return null
     *
     */
    protected function ok($path, $type)
    {
        $this->response =  $this->response
            ->withStatus(200)
            ->withBody(new Stream($path))
            ->withHeader('Content-Length', (string) filesize($path))
            ->withHeader('Content-Type', $type);
    }

    /**
     *
     * Sets a 404 Not Found response.
     *
     * @return null
     *
     */
    protected function notFound()
    {
        $this->response = $this->response->withStatus(404);
        $this->response->getBody()->write("Not found");
    }
}
