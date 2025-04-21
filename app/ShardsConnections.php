<?php

namespace App;

use App\Services\ShardResolver;

trait ShardsConnections
{
    public function useShardConnection($key)
    {
        $this->setConnection(ShardResolver::getConnectionName($key));
        return $this;
    }
}
