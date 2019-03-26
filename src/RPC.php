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

use function Amp\call;
use Amp\Deferred;
use Amp\Promise;

class RPC
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var int
     */
    private $seq = 0;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $method
     * @param string $payload An binary data or array of arguments for complex types.
     * @param int    $flags   Payload control flags.
     *
     * @return mixed
     */
    public function call(string $method, string $payload, int $flags = 0): Promise
    {
        return call(function () use ($method, $payload, $flags) {
            $frame = new Frame(++$this->seq, $method, $payload, $flags);
            $deferred = new Deferred;

            $this->connection->subscribe($frame->seq, static function (Frame $frame) use ($deferred) {
                $deferred->resolve($frame->payload);
            });

            yield $this->connection->send($frame);

            return $deferred->promise();
        });
    }
}
