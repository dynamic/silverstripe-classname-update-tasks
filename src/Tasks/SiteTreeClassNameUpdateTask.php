<?php

namespace Dynamic\ClassNameUpdate\BuildTasks;

use Dynamic\ClassNameUpdate\Traits\ClassKeyTrait;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;

/**
 * Class SiteTreeClassNameUpdateTask
 * @package Dynamic\ClassNameUpdate\BuildTasks
 */
class SiteTreeClassNameUpdateTask extends BuildTask
{
    use ClassKeyTrait;

    /**
     * @var string
     */
    private static $segment = 'site-tree-class-name-task';

    /**
     * @var string
     */
    private static $default_page_class = \Page::class;

    /**
     * SiteTreeClassNameUpdateTask constructor.
     * @param array $key
     */
    public function __construct($key = [])
    {
        $this->setClassKey($key);

        $this->updateAllowedChildrenConfig();

        parent::__construct();
    }

    /**
     *
     */
    private function updateAllowedChildrenConfig()
    {
        foreach ($this->yieldNewPageTypes() as $class) {
            $class::config()->merge($class, 'allowed_children', \Page::class);
        }
    }

    /**
     * @param \SilverStripe\Control\HTTPRequest $request
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function run($request)
    {
        foreach ($this->yieldPages() as $page) {
            $this->updatePage($page);
        }
    }

    /**
     * @param SiteTree $page
     * @throws \SilverStripe\ORM\ValidationException
     */
    private function updatePage(SiteTree $page)
    {
        if ($newClass = $this->getNewPageTypeClassname($page->RecordClassName)) {
            static::write_it("Attempting an update of {$page->RecordClassName} to {$newClass}.");
            $page->ClassName = $newClass;
            $page->write();
            static::write_it("{$page->Title} updated classname.");
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldPages()
    {
        foreach (SiteTree::get() as $page) {
            yield $page;
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldLegacyPageTypes()
    {
        foreach ($this->getClassKey() as $key => $val) {
            yield $key;
        }
    }

    /**
     * @return \Generator
     */
    protected function yieldNewPageTypes()
    {
        foreach ($this->getClassKey() as $key => $val) {
            yield $val;
        }
    }

    /**
     * @param $legacyName
     * @return string
     */
    protected function getNewPageTypeClassname($legacyName)
    {
        if (isset($this->getClassKey()[$legacyName])) {
            return $this->getClassKey()[$legacyName];
        }

        return $this->config()->get('default_page_class');
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
