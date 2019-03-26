<?php
/**
 * This file is part of PHPinnacle/Ridge.
 *
 * (c) PHPinnacle Team <dev@phpinnacle.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PHPinnacle\Amridge;

use Amp\Promise;

class Relay
{
    /** Supported socket types. */
    const SOCK_TCP = 0;
    const SOCK_UNIX = 1;

    // @deprecated
    const SOCK_TPC = self::SOCK_TCP;

    /** @var string */
    private $address;

    /** @var int|null */
    private $port;

    /** @var int */
    private $type;

    /** @var resource|null */
    private $socket;

    /**
     * Example:
     * $relay = new SocketRelay("localhost", 7000);
     * $relay = new SocketRelay("/tmp/rpc.sock", null, Socket::UNIX_SOCKET);
     *
     * @param string   $address Localhost, ip address or hostname.
     * @param int|null $port    Ignored for UNIX sockets.
     * @param int      $type    Default: TCP_SOCKET
     *
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(string $address, int $port = null, int $type = self::SOCK_TCP)
    {
        switch ($type) {
            case self::SOCK_TCP:
                if ($port === null) {
                    throw new Exception\InvalidArgumentException(sprintf(
                        "no port given for TPC socket on '%s'",
                        $address
                    ));
                }
                break;
            case self::SOCK_UNIX:
                $port = null;
                break;
            default:
                throw new Exception\InvalidArgumentException(sprintf(
                    "undefined connection type %s on '%s'",
                    $type,
                    $address
                ));
        }

        $this->address = $address;
        $this->port = $port;
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function receiveAsync(int &$flags = null): Promise
    {
        // TODO: Implement receiveAsync() method.
    }

    /**
     * {@inheritdoc}
     */
    public function send($payload, int $flags = null): self
    {
        $this->connect();

        $size = strlen($payload);
        if ($flags & self::PAYLOAD_NONE && $size != 0) {
            throw new Exception\TransportException("unable to send payload with PAYLOAD_NONE flag");
        }

        socket_send($this->socket, pack('CPJ', $flags, $size, $size), 17, 0);

        if (!($flags & self::PAYLOAD_NONE)) {
            socket_send($this->socket, $payload, $size, 0);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function receiveSync(int &$flags = null)
    {
        $this->connect();

        $prefix = $this->fetchPrefix();
        $flags = $prefix['flags'];
        $result = null;

        if ($prefix['size'] !== 0) {
            $readBytes = $prefix['size'];
            $buffer = null;

            //Add ability to write to stream in a future
            while ($readBytes > 0) {
                $bufferLength = socket_recv(
                    $this->socket,
                    $buffer,
                    min(self::BUFFER_SIZE, $readBytes),
                    MSG_WAITALL
                );

                $result .= $buffer;
                $readBytes -= $bufferLength;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->socket != null;
    }

    /**
     * Ensure socket connection. Returns true if socket successfully connected
     * or have already been connected.
     *
     * @return bool
     *
     * @throws Exception\RelayException
     * @throws \Error When sockets are used in unsupported environment.
     */
    public function connect(): bool
    {
        if ($this->isConnected()) {
            return true;
        }

        $this->socket = $this->createSocket();

        try {
            if (socket_connect($this->socket, $this->address, $this->port) === false) {
                throw new Exception\RelayException(socket_strerror(socket_last_error($this->socket)));
            }
        } catch (\Exception $e) {
            throw new Exception\RelayException("unable to establish connection {$this}", 0, $e);
        }

        return true;
    }

    /**
     * Close connection.
     *
     * @throws Exception\RelayException
     */
    public function close()
    {
        if (!$this->isConnected()) {
            throw new Exception\RelayException("unable to close socket '{$this}', socket already closed");
        }

        socket_close($this->socket);
        $this->socket = null;
    }

    /**
     * Destruct connection and disconnect.
     */
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    /**
     * @return array Prefix [flag, length]
     *
     * @throws Exception\PrefixException
     */
    private function fetchPrefix(): array
    {
        $prefixLength = socket_recv($this->socket, $prefixBody, 17, MSG_WAITALL);
        if ($prefixBody === false || $prefixLength !== 17) {
            throw new Exception\PrefixException(sprintf(
                "unable to read prefix from socket: %s",
                socket_strerror(socket_last_error($this->socket))
            ));
        }

        $result = unpack("Cflags/Psize/Jrevs", $prefixBody);
        if (!is_array($result)) {
            throw new Exception\PrefixException("invalid prefix");
        }

        if ($result['size'] != $result['revs']) {
            throw new Exception\PrefixException("invalid prefix (checksum)");
        }

        return $result;
    }
}
