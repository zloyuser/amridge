<?php
/**
 * This file is part of PHPinnacle/Amridge.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PHPinnacle\Amridge;

final class Parser
{
    /**
     * @var Buffer
     */
    private $buffer;

    /**
     * @param Buffer|null $buffer
     */
    public function __construct(Buffer $buffer = null)
    {
        $this->buffer = $buffer ?: new Buffer;
    }

    /**
     * @param string $chunk
     *
     * @return void
     */
    public function append(string $chunk): void
    {
        $this->buffer->append($chunk);
    }

    /**
     * @return null|Frame
     * @throws \PHPinnacle\Buffer\BufferOverflow
     */
    public function parse(): ?Frame
    {
        if ($this->buffer->size() < 17) {
            return null;
        }

        $flags   = $this->buffer->readUint8(0);

        // $sizeLE = $this->buffer->readUint64(9); TODO check size

        $sizeBE  = $this->buffer->readUint64(9);

        if ($this->buffer->size() < $sizeBE + 17) {
            return null;
        }

        $this->buffer->discard(17);

        $method = $this->buffer->consume($sizeBE - 8);
        $seq    = (int) \unpack("P", $this->buffer->consume(8));
        $body   = $this->consumeBody();

        return new Frame($seq, $method, $body, $flags);
    }

    /**
     * @return string
     * @throws \PHPinnacle\Buffer\BufferOverflow
     */
    private function consumeBody(): string
    {
        $flags = $this->buffer->consumeUint8();

        $this->buffer->discard(8); // TODO check size

        $sizeBE = $this->buffer->consumeUint64();

        return $this->buffer->consume($sizeBE);
    }
}
