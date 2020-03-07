<?php
/**
 * Amazon S3 component that backs up file data.
 * Simple Modification to Backup your Blesta System to Other s3 Compatible sources
 * Modification from the Blesta Modules Team: 
 * https://github.com/blestamodules/custom-backup-s3-endpoints/
 *
 * @package blesta
 * @subpackage blesta.components.net.amazon_s3
 * @copyright Copyright (c) 2010, Phillips Data, Inc.
 * @license http://www.blesta.com/license/ The Blesta License Agreement
 * @link http://www.blesta.com/ Blesta
 */
class AmazonS3 extends S3
{
    /**
     * @var string The S3 region
     */
    private $region = null;
    /**
     * @var array Key/value pairs of regions to endpoint mappings
     */
    private static $locations = [
        'us-east-1' => 's3.amazonaws.com',
        'us-west-2' => 's3-us-west-2.amazonaws.com',
        'us-west-1' => 's3-us-west-1.amazonaws.com',
        'eu-west-1' => 's3-eu-west-1.amazonaws.com',
        'ap-southeast-1' => 's3-ap-southeast-1.amazonaws.com',
        'ap-southeast-2' => 's3-ap-southeast-2.amazonaws.com',
        'ap-northeast-1' => 's3-ap-northeast-1.amazonaws.com',
        'sa-east-1' => 's3-sa-east-1.amazonaws.com',
        'stackpath-us-east-1' => 's3.us-east.stackpathstorage.com',
        'stackpath-us-west-1' => 's3.us-west.stackpathstorage.com',
        'stackpath-eu-central-1' => 's3.eu-central.stackpathstorage.com',
        'digitalocean-sfo-2' => 'sfo2.digitaloceanspaces.com',
        'digitalocean-nyc-2' => 'nyc3.digitaloceanspaces.com',
        'digitalocean-ams-3' => 'ams3.digitaloceanspaces.com',
        'digitalocean-sgp-1' => 'sgp1.digitaloceanspaces.com',
        'digitalocean-fra-1' => 'fra1.digitaloceanspaces.com',
    ];
    /**
     * @var array Key/value pairs of regions to region name mappings
     */
    private static $regions = [
        'us-east-1' => 'Amazon US Standard',
        'us-west-2' => 'Amazon US West (Oregon) Region',
        'us-west-1' => 'Amazon US West (Northern California) Region',
        'eu-west-1' => 'Amazon EU (Ireland) Region',
        'ap-southeast-1' => 'Amazon Asia Pacific (Singapore) Region',
        'ap-southeast-2' => 'Amazon Asia Pacific (Sydney) Region',
        'ap-northeast-1' => 'Amazon Asia Pacific (Tokyo) Region',
        'sa-east-1' => 'Amazon South America (Sao Paulo) Region',
        'stackpath-us-east-1' => 'StackPath US East',
        'stackpath-us-west-1' => 'StackPath US West',
        'stackpath-eu-central-1' => 'StackPath EU Central',
        'digitalocean-sfo-2' => 'DigitalOcean San Francisco 2',
        'digitalocean-nyc-2' => 'DigitalOcean New York 3',
        'digitalocean-ams-3' => 'DigitalOcean Amsterdam 3',
        'digitalocean-sgp-1' => 'DigitalOcean Singapore 1',
        'digitalocean-fra-1' => 'DigitalOcean Frankfurt 1',
    ];

    /**
     * Constructs a new AmazonS3 component, setting the credentials
     *
     * @param string $access_key The access key
     * @param string $secret_key The secret key
     * @param bool $use_ssl Whether or not to use SSL when communicating
     * @param string $region The S3 region name
     */
    public function __construct($access_key, $secret_key, $use_ssl = true, $region = null)
    {
        $this->region = $region;
        $endpoint = isset(self::$locations[$region]) ? self::$locations[$region] : self::$locations['us-east-1'];

        parent::__construct($access_key, $secret_key, $use_ssl, $endpoint);
    }

    /**
     * Returns the region currently set
     *
     * @return string The S3 region name
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Returns an array of key/value pair region to region name mappings
     *
     * @return array A key/value pair of region to region name mappings
     */
    public static function getRegions()
    {
        return self::$regions;
    }

    /**
     * Uploads a file to Amazon S3
     *
     * @param string $file The full path of the file on the local system to upload
     * @param string $bucket The name of the bucket to upload to
     * @param string $remote_file_name The name of the file on the S3 server, null will default to the
     *  same file name as the local file
     * @return bool True if the file was successfully uploaded, false otherwise
     */
    public function upload($file, $bucket, $remote_file_name = null)
    {
        if (!file_exists($file)) {
            return false;
        }

        if ($remote_file_name === null) {
            $remote_file_name = baseName($file);
        }

        if ($this->putObjectFile($file, $bucket, $remote_file_name)) {
            return true;
        }
        return false;
    }
}
