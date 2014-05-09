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
 * @date 17/04/14.04.2014 16:42
 */

namespace Mindy\Utils;


use Mindy\Orm\QuerySet;

class Pager
{
    public $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function paginate($page = 1, $pageSize = 10)
    {
        $newData = [];
        if($this->data instanceof QuerySet) {
            $newData = $this->data->paginate($page, $pageSize);
        } elseif(is_array($this->data)) {
            $newData = array_slice($this->data, $pageSize * ($page <= 1 ? 0 : $page - 1), $pageSize);
        }
        return $newData;
    }
}
