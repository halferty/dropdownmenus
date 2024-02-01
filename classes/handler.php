<?php
namespace halferty\dropdownmenus\classes;

class handler
{
    protected $db;
    protected $dropdownmenus_table;

    public function __construct(\phpbb\db\driver\driver_interface $db, $dropdownmenus_table)
    {
        $this->db = $db;
        $this->dropdownmenus_table = $dropdownmenus_table;
    }

    public function get_dropdownmenus($cache=true)
    {
        $sql = 'SELECT * FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_enabled = 1';

        if ($cache) {
            $result = $this->db->sql_query($sql, 600);
        } else {
            $result = $this->db->sql_query($sql);
        }
		$rows = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

        // Dropdownmenus either have a parent or they are a top level menu
        $top_level_menus = array();
        foreach ($rows as $row) {
            if ($row['dropdownmenu_parent'] == 0) {
                $row['children'] = array();
                foreach ($rows as $child) {
                    if ($child['dropdownmenu_parent'] == $row['dropdownmenu_id']) {
                        $row['children'][] = $child;
                    }
                }
                // Sort children by position
                usort($row['children'], function($a, $b) {
                    return $a['dropdownmenu_position'] - $b['dropdownmenu_position'];
                });
                $top_level_menus[] = $row;
            }
        }
        // Sort top level menus by position
        usort($top_level_menus, function($a, $b) {
            return $a['dropdownmenu_position'] - $b['dropdownmenu_position'];
        });

        return $top_level_menus;
    }

    public function create_dropdownmenu($title, $description, $link, $position, $enabled)
    {
        $this->move_conflicting_menus_down($position);
        $sql_ary = array(
            'dropdownmenu_title' => $title,
            'dropdownmenu_description' => $description,
            'dropdownmenu_link' => $link,
            'dropdownmenu_position' => $position,
            'dropdownmenu_enabled' => $enabled,
        );
        $this->db->sql_query('INSERT INTO ' . $this->dropdownmenus_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
    }

    public function update_dropdownmenu($toplevelid, $title, $description, $link, $position, $enabled)
    {
        $this->move_conflicting_menus_down($position);
        $sql_ary = array(
            'dropdownmenu_title' => $title,
            'dropdownmenu_description' => $description,
            'dropdownmenu_link' => $link,
            'dropdownmenu_position' => $position,
            'dropdownmenu_enabled' => $enabled,
        );
        $this->db->sql_query('UPDATE ' . $this->dropdownmenus_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE dropdownmenu_id = ' . (int) $toplevelid);
    }

    public function create_dropdownitem($toplevelid, $title, $description, $link, $position, $enabled)
    {
        $this->move_conflicting_items_down($position, $toplevelid);
        $sql_ary = array(
            'dropdownmenu_title' => $title,
            'dropdownmenu_description' => $description,
            'dropdownmenu_link' => $link,
            'dropdownmenu_position' => $position,
            'dropdownmenu_enabled' => $enabled,
            'dropdownmenu_parent' => $toplevelid,
        );
        $this->db->sql_query('INSERT INTO ' . $this->dropdownmenus_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
    }

    public function update_dropdownitem($childid, $title, $description, $link, $position, $enabled)
    {
        $sql = 'SELECT dropdownmenu_parent FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_id = ' . (int) $childid;
        $result = $this->db->sql_query($sql);
        $toplevelid = (int) $this->db->sql_fetchfield('dropdownmenu_parent');
        $this->db->sql_freeresult($result);
        $this->move_conflicting_items_down($position, $toplevelid);
        $sql_ary = array(
            'dropdownmenu_title' => $title,
            'dropdownmenu_description' => $description,
            'dropdownmenu_link' => $link,
            'dropdownmenu_position' => $position,
            'dropdownmenu_enabled' => $enabled,
        );
        $this->db->sql_query('UPDATE ' . $this->dropdownmenus_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE dropdownmenu_id = ' . (int) $childid);
    }

    public function delete_dropdownmenu($toplevelid)
    {
        $this->db->sql_query('DELETE FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_id = ' . (int) $toplevelid);
    }

    public function delete_dropdownitem($childid)
    {
        $this->db->sql_query('DELETE FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_id = ' . (int) $childid);
    }

    private function move_conflicting_menus_down($position)
    {
        $done = false;
        $positiontemp = $position;
        while (!$done) {
            $sql = 'SELECT * FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_position = ' . (int) $position . ' AND dropdownmenu_parent = 0 AND dropdownmenu_enabled = 1';
            $result = $this->db->sql_query($sql);
            $rows = $this->db->sql_fetchrowset($result);
            $this->db->sql_freeresult($result);
            if (empty($rows)) {
                $done = true;
            } else {
                $positiontemp2 = $positiontemp;
                foreach ($rows as $row) {
                    $this->db->sql_query('UPDATE ' . $this->dropdownmenus_table . ' SET dropdownmenu_position = ' . $positiontemp2 . ' WHERE dropdownmenu_id = ' . (int) $row['dropdownmenu_id']);
                    $positiontemp2++;
                }
            }
            $positiontemp++;
        }
    }

    private function move_conflicting_items_down($position, $toplevelid)
    {
        $done = false;
        $positiontemp = $position;
        while (!$done) {
            $sql = 'SELECT * FROM ' . $this->dropdownmenus_table . ' WHERE dropdownmenu_position = ' . (int) $position . ' AND dropdownmenu_parent = ' . (int) $toplevelid . ' AND dropdownmenu_enabled = 1';
            $result = $this->db->sql_query($sql);
            $rows = $this->db->sql_fetchrowset($result);
            $this->db->sql_freeresult($result);
            if (empty($rows)) {
                $done = true;
            } else {
                $positiontemp2 = $positiontemp;
                foreach ($rows as $row) {
                    $this->db->sql_query('UPDATE ' . $this->dropdownmenus_table . ' SET dropdownmenu_position = ' . $positiontemp2 . ' WHERE dropdownmenu_id = ' . (int) $row['dropdownmenu_id']);
                    $positiontemp2++;
                }
            }
            $positiontemp++;
        }
    }
}
