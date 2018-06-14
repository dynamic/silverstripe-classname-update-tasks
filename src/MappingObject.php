<?php

namespace Dynamic\ClassNameUpdate;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MappingObject
 * @package Dynamic\ClassNameUpdate
 */
class MappingObject
{
    use Configurable;
    use Injectable;

    /**
     * @var
     */
    private $mapping_file_path;

    /**
     * @var
     */
    private $upgrade_mapping;

    /**
     * MappingObject constructor.
     * @param null $path
     */
    public function __construct($path = null)
    {
        $this->setMappingPath($path);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setMappingPath($path)
    {
        $this->mapping_file_path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMappingPath()
    {
        if (!$this->mapping_file_path) {
            $this->setMappingPath();
        }
        return $this->mapping_file_path;
    }

    /**
     * @return $this
     */
    public function setUpgradeMapping()
    {
        $parsed = Yaml::parseFile($this->getMappingPath());

        $this->upgrade_mapping = $parsed['mappings'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpgradeMapping()
    {
        if (!$this->upgrade_mapping) {
            $this->setUpgradeMapping();
        }
        return $this->upgrade_mapping;
    }
}
