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

final class Frame
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
        $flags = Frame::PAYLOAD_CONTROL | Frame::PAYLOAD_RAW;

        $buffer
            ->appendUint8($flags)
            ->append(\pack("P", $sizeM))
            ->appendUint64($sizeM)
            ->append($this->method)
            ->append(\pack("P", $this->seq))
        ;

        $payload = json_encode($this->payload);
        $sizeP = \strlen($payload);

        //if ($this->flags & self::PAYLOAD_RAW) {
            $buffer
                ->appendUint8($this->flags)
                ->append(\pack("P", $sizeP))
                ->appendUint64($sizeP)
                ->append($payload)
            ;
        //}

        return $buffer->flush();
    }
}
