<?php

namespace App\Console\Commands;

use App\Context\CFUpdate;
use App\Context\IpAddress;
use Illuminate\Console\Command;

/**
 * Class CFUpdate
 * @package App\Console\Commands
 */
class CFUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cf:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get your IP address';

    /** @var string */
    private $zoneRecord;

    /** @var CFUpdate */
    protected $cfUpdate;

    /**
     * CFUpdate constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cfUpdate = new CFUpdate(
            env('CLOUDFLARE_EMAIL'), env('CLOUDFLARE_API_KEY'),
            env('CLOUDFLARE_ZONE_ID'), env('CLOUDFLARE_ZONE_RECORD')
        );

        $this->zoneRecord = env('CLOUDFLARE_ZONE_RECORD');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yourIp = app(IpAddress::class)->handle();

        $updatedZoneRecord = $this->cfUpdate->updateZoneRecord($yourIp);

        if ($updatedZoneRecord) {
            $this->info('CloudFlare '.$this->zoneRecord.' updated to: '.$yourIp);
        } else {
            $this->warn('Cant get the specify zone record - creating.');
            $created = $this->cfUpdate->createZoneRecord($yourIp);

            if($created === true) {
                $this->info($this->zoneRecord.' with '.$yourIp.' created.');
            } else {
                $this->error('Sorry, but I cant create the '.$this->zoneRecord);
            }
        }
    }
}
