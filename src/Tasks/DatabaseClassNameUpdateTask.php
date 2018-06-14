<?php

namespace Dynamic\ClassNameUpdate\BuildTasks;

use Dynamic\ClassNameUpdate\MappingObject;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;

/**
 * Class DatabaseClassNameUpdateTask
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class DatabaseClassNameUpdateTask extends BuildTask
{
    /**
     * @var
     */
    private $mapping_object;

    /**
     * @var
     */
    private $mapping;

    /**
     * @var string
     */
    private static $upgrade_file_path;

    /**
     * @var string
     */
    private static $segment = 'database-classname-update-task';

    /**
     * @var string
     */
    protected $title = 'Database ClassName Update Task';

    /**
     * @var string
     */
    protected $description = "Update ClassName data for a SilverStripe 3 to SilverStripe 4 migration. Be sure to set the absolute path to the .upgrade.yml file for this task before running it or nothing will happen.";

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     */
    public function run($request)
    {
        if (!$this->config()->get('upgrade_file_path') || !file_exists($this->config()->get('upgrade_file_path'))) {
            $class = static::class;
            echo "You must specify the configuration variable: 'upgrade_file_path' for '{$class}'\n";
            return;
        }
        $mapping = $this->getMappingObject();

        $this->updateClassNameColumns($mapping);

        echo "Database ClassName data has been updated\n";
    }

    /**
     * @param $mapping
     */
    protected function updateClassNameColumns($mapping)
    {
        foreach ($this->getMapping() as $key => $val) {
            $ancestry = ClassInfo::ancestry($val);
            $ancestry = array_merge(array_values($ancestry), array_values($ancestry));
            if (in_array(DataObject::class, $ancestry)) {
                $queryClass = $ancestry[array_search(DataObject::class, $ancestry) + 1];
                foreach ($this->yieldRecords($queryClass, $key) as $record) {
                    $this->updateRecord($record, $val);
                }
            }
        }
    }

    /**
     * @param $record
     * @param $updatedClassName
     */
    protected function updateRecord($record, $updatedClassName)
    {
        $record->ClassName = $updatedClassName;
        $record->write();
    }

    /**
     * @return $this
     */
    public function setMappingObject()
    {
        $mapping = MappingObject::singleton();
        $mapping->setMappingPath($this->config()->get('upgrade_file_path'));

        $this->mapping_object = $mapping;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getMappingObject()
    {
        if (!$this->mapping instanceof MappingObject) {
            $this->setMappingObject();
        }
        return $this->mapping_object;
    }

    /**
     * @return $this
     */
    protected function setMapping()
    {
        $this->mapping = $this->getMappingObject()->getUpgradeMapping();
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getMapping()
    {
        if (!$this->mapping) {
            $this->setMapping();
        }
        return $this->mapping;
    }

    /**
     * @param $singleton
     * @param $legacyName
     * @return \Generator
     */
    public function yieldRecords($class, $legacyName)
    {
        foreach ($class::get()->filter('ClassName', $legacyName) as $object) {
            yield $object;
        }
    }
}
