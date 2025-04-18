<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class SetupReplication extends Command
{
    protected $signature = 'db:setup-replication';
    protected $description = 'Set up master-slave replication between databases';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Setting up master-slave replication...');

        // Step 1: Create replication user on master
        DB::connection('mysql')->statement("
            CREATE USER 'replica'@'%' IDENTIFIED BY 'replica_password';
        ");
        DB::connection('mysql')->statement("
            GRANT REPLICATION SLAVE ON *.* TO 'replica'@'%';
        ");
        DB::connection('mysql')->statement("FLUSH PRIVILEGES;");

        // Step 2: Get Master Log File and Position
        $masterStatus = DB::connection('mysql')->select("SHOW MASTER STATUS")[0];
        $logFile = $masterStatus->File;
        $logPos  = $masterStatus->Position;

        $this->info("Master Log File: {$logFile}, Position: {$logPos}");

        // Step 3: Set up replication on slave
        DB::connection('mysql_replica')->statement("
            CHANGE MASTER TO
                MASTER_HOST='mysql_primary',
                MASTER_USER='replica',
                MASTER_PASSWORD='replica_password',
                MASTER_LOG_FILE='{$logFile}',
                MASTER_LOG_POS={$logPos};
        ");

        // Step 4: Start slave replication
        DB::connection('mysql_replica')->statement("START SLAVE;");
        
        $this->info('Replication setup complete!');
    }
}
