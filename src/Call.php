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

final class Call
{
    const
        PAYLOAD_NONE    = 2,
        PAYLOAD_RAW     = 4,
        PAYLOAD_ERROR   = 8,
        PAYLOAD_CONTROL = 16
    ;

    /**
     * @var int
     */
    public $seq;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $payload;

    /**
     * @var int
     */
    public $flags;

    /**
     * @param int    $seq
     * @param string $method
     * @param string $payload
     * @param int    $flags
     */
    public function __construct(int $seq, string $method, string $payload = '', int $flags = 0)
    {
        $this->seq     = $seq;
        $this->method  = $method;
        $this->payload = $payload;
        $this->flags   = $flags;
    }

    /**
     * @param Buffer $buffer
     *
     * @return string
     * @throws Exception\TransportException
     */
    public function pack(Buffer $buffer) :string
    {
        $sizeM = \strlen($this->method) + 8;
        $sizeP = \strlen($this->payload);

        if ($this->flags & self::PAYLOAD_NONE && $sizeP != 0) {
            throw new Exception\TransportException("Unable to send payload with PAYLOAD_NONE flag");
        }

        $buffer
            ->appendUInt8(Frame::PAYLOAD_CONTROL | Frame::PAYLOAD_RAW)
            ->appendUint64($sizeM)
            ->append(\pack("J", $sizeP))
            ->append($this->method)
            ->append(\pack("P", $this->seq))
        ;

        if ($this->flags & self::PAYLOAD_RAW) {
            $buffer
                ->appendUInt8($this->flags)
                ->appendUint64($sizeP)
                ->append(\pack("J", $sizeP))
                ->append($this->payload)
            ;
        }

        return $buffer->flush();
    }
}
