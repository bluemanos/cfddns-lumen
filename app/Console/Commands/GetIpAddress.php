<?php

namespace App\Console\Commands;

use App\Context\IpAddress;
use Illuminate\Console\Command;

/**
 * Class GetIpAddress
 * @package App\Console\Commands
 */
class GetIpAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get your IP address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var IpAddress $ipAddress */
        $ipAddress = app(IpAddress::class);

        $this->info('My IP address is: '.$ipAddress->handle());
    }
}