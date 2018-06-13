<?php

namespace Dynamic\ClassNameUpdate\BuildTasks;

use Dynamic\ClassNameUpdate\Traits\ClassKeyTrait;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DataObject;

/**
 * Class DataObjectClassNameUpdate
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class DataObjectClassNameUpdate extends BuildTask
{
    use ClassKeyTrait;

    /**
     * @var string
     */
    private static $segment = 'dataobject-class-name-task';

    /**
     * SiteTreeClassNameUpdateTask constructor.
     * @param array $key
     */
    public function __construct($key = [])
    {
        $this->setClassKey($key);

        parent::__construct();
    }

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function run($request)
    {
        foreach ($this->yieldNewObjectTypes() as $class) {
            foreach ($this->yieldObjects($class) as $object) {
                $this->updateObject($object);
            }
        }
    }

    /**
     * @param DataObject $object
     * @throws \SilverStripe\ORM\ValidationException
     */
    private function updateObject(DataObject $object)
    {
        if ($newClass = $this->getNewObjectClassname($object->RecordClassName)) {
            if ($newClass !== false) {
                static::write_it("Attempting an update of {$object->RecordClassName} to {$newClass}.");
                $object->ClassName = $newClass;
                $object->write();
                static::write_it("{$object->Title} updated classname.");

            } else {
                static::write_it("Could not update {$object->Title}'s classname");
            }
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldObjects($class)
    {
        foreach ($class::get() as $object) {
            yield $object;
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldLegacyObjectTypes()
    {
        foreach ($this->getClassKey() as $key => $val) {
            yield $key;
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldNewObjectTypes()
    {
        foreach ($this->getClassKey() as $key => $val) {
            yield $val;
        }
    }

    /**
     * @param $legacyName
     * @return string
     */
    protected function getNewObjectClassname($legacyName)
    {
        if (isset($this->getClassKey()[$legacyName])) {
            return $this->getClassKey()[$legacyName];
        }
        return false;
    }

    /**
     * @param string $message
     */
    protected function write_it($message = '')
    {
        if (Director::is_cli()) {
            echo "{$message}\n";
        } else {
            echo "{$message}<br><br>";
        }
    }
}
