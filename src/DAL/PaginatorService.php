<?php

namespace App\DAL;

use App\DAL\DatabaseService;


/**
 * Универсальный класс пагинатора для сущностей из БД
 */
class PaginatorService extends DatabaseService
{

    private $limit;
    private $page;
    private $total;


    /**
     * Создание линков пагинации
     * @param mixed $links - max число линков справа и слева от середины
     * @param mixed $tableName
     * @return html links
     */
    public function createLinks($links, $tableName)
    {
        $last = ceil($this->total / $this->limit);

        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;
        $end  = (($this->page + $links) < $last) ? $this->page + $links : $last;

        $html = "<ul class='pagination justify-content-center'>";
        $class = ($this->page == 1) ? "'page-item disabled'" : "'page-item'";
        $href = "?tableName=" . $tableName . "&page=" . $this->page - 1;
        $html .= "<li class=" . $class . "><a class='page-link' href='" . $href . "'>&laquo;</a></li>";

        if ($start > 1) {
            $html   .= "<li class='page-item'><a class='page-link' href='?tableName=" . $tableName . "&page=1'>1</a></li>";
            $html   .= "<li class='page-item disabled'><span>...</span></li>";
        }

        for ($i = $start; $i <= $end; $i++) {
            $class  = ($this->page == $i) ? "'page-item active'" : "'page-item'";
            $html .= "<li class=" . $class . "><a class='page-link' href='?tableName=" . $tableName . "&page=" . $i . "'>" . $i . "</a></li>";
        }

        if ($end < $last) {
            $html.= "<li class='page-item disabled'><span>...</span></li>";
            $html.= "<li class='page-item'><a class='page-link' href='?tableName=" . $tableName . "&page="  . $last . "'>" . $last . "</a></li>";
        }

        $class = ($this->page == $last) ? "'page-item disabled'" : "'page-item'";
        $html       .= "<li class=" . $class . "><a class='page-link' href='?tableName=" . $tableName . "&page=" . ($this->page + 1) . "'>&raquo;</a></li>";

        $html       .= "</ul>";
        return $html;
    }

    /**
     *  Универсальный метод для пагинации сущностей из базы данных
     * @param mixed $tableName
     * @param mixed $page
     * @param mixed $pageSize
     * @return list entities, count entities
     */
    public function getRecords($tableName, $page, $order = "ORDER BY CreatedOn", $pageSize = 5)
    {
        $this->page = $page;
        $this->limit = $pageSize;
        $paginationStart = ($page - 1) * $pageSize;
        $query = "SELECT * FROM " . $tableName . " " . $order . " " . "LIMIT ? OFFSET ?;";
        $records = $this->executeQuery($query, "ii", array($pageSize, $paginationStart));
        return $records;
    }

    /**
     * Универсальный метод для получения числа записей в таблице
     * @param mixed $fieldName
     * @param mixed $tableName
     * @return int count
     */
    public function getCounts($fieldName, $tableName)
    {
        $cnt = $this->executeQuery("SELECT count(" . $fieldName . ") AS cnt FROM " . $tableName . ";", "")[0]["cnt"];
        $this->total = $cnt;
        return $cnt;
    }
}
