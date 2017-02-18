<?php
namespace Hkt\Psr7Asset;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /**
     * @var $regx Regular expression matching asset route
     */
    protected $regx;

    public function __construct($regx = '/\/asset\/([a-zA-Z0-9-_]+)\/([a-zA-Z0-9-_]+)\/(.*)/')
    {
        $this->regx = $regx;
    }

    public function match(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();

        if (preg_match($this->regx, $path, $matches)) {
            return (object) [
                'vendor' => $matches[1],
                'package' => $matches[2],
                'file' => $matches[3],
            ];
        }

        return false;
    }
}
