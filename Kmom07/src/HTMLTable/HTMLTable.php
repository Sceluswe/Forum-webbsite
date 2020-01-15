<?php
namespace Anax\HTMLTable;

/**
* Model for creating tables.
*/
class HTMLTable
{



    /**
    * Creates tables and returns it in a string.
    *
    * @param array, an array of arrays, first index is the table header.
    *
    * @return string, string with the table in HTML format.
    */
    public function createTable($data)
    {
        $table =  isset($data['class']) ? "<table class=\"{$data['class']}\">" : '<table>';
        $headers = true;

        foreach ($data as $item) {
            if (is_array($item)) {
                $table .= isset($item['class']) ? "<tr class=\"{$item['class']}\">" : '<tr>';
                unset($item['class']);

                if ($headers) {
                    foreach ($item as $index) {
                        $table .= "<th>{$index}</th>";
                    }

                    $headers = false;
                } else {
                    foreach ($item as $index) {
                        $table .= "<td>{$index}</td>";
                    }
                }

                $table .= "</tr>";
            }
        }

        $table .= "</table>";

        return $table;
    }
}
