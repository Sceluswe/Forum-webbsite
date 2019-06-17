<?php
namespace Anax\HTMLTable;

/**
 * Model for Users.
 * 
 * Contains interactions with the database.
 */
class HTMLTable
{
    public function createTable($data)
    {
        $table =  isset($data['class']) ? "<table class='{$data['class']}'>" : '<table>';
        $i = 0;
        
        foreach($data as $item)
        {
            if(is_array($item))
            {
                $table .= isset($item['class']) ? "<tr class='{$item['class']}'>" : '<tr>';
                unset($item['class']);
                
                if($i == 0)
                {
                    foreach($item as $index)
                    {
                        $table .= "<th>{$index}</th>";
                    }
                    
                    $i++;
                }
                else
                {
                    foreach($item as $index)
                    {
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