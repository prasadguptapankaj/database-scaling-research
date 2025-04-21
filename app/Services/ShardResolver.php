<?php

namespace App\Services;

class ShardResolver
{
    public static function getConnectionByEmail(string $email): string
    {
        $shardKey = crc32($email);

        // Simple modulus-based sharding logic (customize as needed)
        $shardNumber = $shardKey % 2; // if 2 shards: 0,1

        return match ($shardNumber) {
            0 => 'mysql',
            1 => 'mysql_shard_2',
        };
    }
}
