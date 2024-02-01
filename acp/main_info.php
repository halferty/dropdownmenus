<?php

namespace halferty\dropdownmenus\acp;

class main_info
{
    public function module()
    {
        return array(
            'filename'  => '\halferty\dropdownmanus\acp\main_module',
            'title'     => 'Dropdown Menus',
            'modes'    => array(
                'settings'  => array(
                    'title' => 'Settings',
                    'auth'  => 'ext_halferty/dropdownmenus && acl_a_board',
                    'cat'   => array('Dropdown Menus'),
                ),
            ),
        );
    }
}
