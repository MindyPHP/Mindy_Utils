<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 03/04/14.04.2014 12:39
 */

namespace Mindy\Utils;

trait TitleBreadcrumbsTrait
{
    public $titleSortAsc = true;

    private $_breadcrumbs = [];

    private $_title = [];

    /**
     * @param $value array|string
     * @return $this
     */
    public function setBreadcrumbs($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $this->_breadcrumbs = $value;
        return $this;
    }

    /**
     * @param $name
     * @param $url
     * @return $this
     */
    public function addBreadcrumb($name, $url = null, $items = [])
    {
        $this->_breadcrumbs[] = [
            'name' => $name,
            'url' => $url,
            'items' => $items
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->_breadcrumbs;
    }

    /**
     * @param $value array|string
     * @return $this
     */
    public function setTitle($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        $this->_title = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getTitle()
    {
        $title = $this->_title;
        if ($this->titleSortAsc) {
            krsort($title);
        }
        return $title;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPageTitle($value)
    {
        return $this->setTitle($value);
    }

    /**
     * @return array
     */
    public function getPageTitle()
    {
        return $this->getTitle();
    }
}
