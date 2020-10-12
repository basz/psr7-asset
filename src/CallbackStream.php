<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @see       http://github.com/zendframework/zend-diactoros for the canonical source repository
 * @copyright Copyright (c) 2015-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-diactoros/blob/master/LICENSE.md New BSD License
 *
 * This class is copied from https://github.com/zendframework/zend-diactoros
 * so that other psr-7 implementations can use the package without installing
 * zendframework/zend-diactoros
 */

namespace Hkt\Psr7Asset;

use InvalidArgumentException;
use RuntimeException;
use Psr\Http\Message\StreamInterface;

/**
 * Implementation of PSR HTTP streams
 */
class CallbackStream implements StreamInterface
{
    /**
     * @var callable|null
     */
    protected $callback;

    /**
     * @param callable $callback
     * @throws InvalidArgumentException
     */
    public function __construct(callable $callback)
    {
        $this->attach($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        $this->callback = null;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(): callable
    {
        $callback = $this->callback;
        $this->callback = null;
        return $callback;
    }

    /**
     * Attach a new callback to the instance.
     *
     * @param callable $callback
     * @throws InvalidArgumentException for callable callback
     */
    public function attach(callable $callback): void
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        throw new RuntimeException('Callback streams cannot tell position');
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        return empty($this->callback);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        throw new RuntimeException('Callback streams cannot seek position');
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        throw new RuntimeException('Callback streams cannot rewind position');
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function write($string): int
    {
        throw new RuntimeException('Callback streams cannot write');
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        throw new RuntimeException('Callback streams cannot read');
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        $callback = $this->detach();
        return $callback ? $callback() : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        $metadata = [
            'eof' => $this->eof(),
            'stream_type' => 'callback',
            'seekable' => false
        ];

        if (null === $key) {
            return $metadata;
        }

        if (! array_key_exists($key, $metadata)) {
            return null;
        }

        return $metadata[$key];
    }
}
