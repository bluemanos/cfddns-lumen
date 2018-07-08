<?php

namespace App\Context;

use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use Cloudflare\API\Endpoints\Zones;
use Cloudflare\API\Adapter\Guzzle;

/**
 * Class CFUpdate
 * @package App\Context
 */
class CFUpdate
{
    /** @var string */
    protected $zoneId;

    /** @var string */
    protected $zoneRecord;

    /** @var DNS */
    protected $dns;

    /** @var Zones */
    protected $zones;

    /**
     * CFUpdate constructor.
     * @param string $cfEmail
     * @param string $apiKey
     * @param string $zoneId
     * @param string $zoneRecord
     */
    public function __construct(string $cfEmail, string $apiKey, string $zoneId, string $zoneRecord)
    {
        $this->zoneId = $zoneId;
        $this->zoneRecord = $zoneRecord;

        $adapter = new Guzzle(new APIKey($cfEmail, $apiKey));

        $this->dns = new DNS($adapter);
        $this->zones = new Zones($adapter);
    }

    /**
     * @param string $yourIp
     *
     * @return bool
     */
    public function updateZoneRecord(string $yourIp)
    {
        $dnsRecords = $this->dns->listRecords($this->zoneId, 'A', $this->zoneRecord);

        if (!empty($dnsRecords->result)) {
            $this->dns->updateRecordDetails($this->zoneId, $dnsRecords->result[0]->id, [
                'type' => 'A',
                'name' => $this->zoneRecord,
                'content' => $yourIp,
            ]);

            return true;
        }

        return false;
    }

    /**
     * @param string $yourIp
     *
     * @return bool
     */
    public function createZoneRecord(string $yourIp)
    {
        return $this->dns->addRecord($this->zoneId, 'A', $this->zoneRecord, $yourIp);
    }
}
