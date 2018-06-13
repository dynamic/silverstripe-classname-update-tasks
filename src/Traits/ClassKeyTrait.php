<?php

namespace Dynamic\ClassNameUpdate\Traits;

/**
 * Trait ClassKeyTrait
 * @package Dynamic\SargentoFSI\Traits
 */
trait ClassKeyTrait
{
    /**
     * @var
     */
    private $class_key;

    /**
     * @return mixed
     */
    private function getClassKey()
    {
        if (!$this->class_key) {
            $this->setClassKey();
        }
        return $this->class_key;
    }

    /**
     * @param array $key
     * @return $this
     */
    public function setClassKey($key = [])
    {
        $this->class_key = $key;

        return $this;
    }
}
