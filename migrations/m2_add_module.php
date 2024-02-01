<?php

namespace halferty\dropdownmenus\migrations;

class m2_add_module extends \phpbb\db\migration\migration
{
    /**
     * If our config variable already exists in the db
     * skip this migration.
     */
    public function effectively_installed()
    {
        return isset($this->config['halferty_dropdownmenus_installed']);
    }

    /**
     * This migration depends on phpBB's v314 migration
     * already being installed.
     */
    static public function depends_on()
    {
        return array('\phpbb\db\migration\data\v31x\v314');
    }

    public function update_data()
    {
        return array(
            array('config.add', array('halferty_dropdownmenus_installed', 1)),

            // Add a parent module (ACP_DEMO_TITLE) to the Extensions tab (ACP_CAT_DOT_MODS)
            array('module.add', array(
                'acp',
                'ACP_CAT_DOT_MODS',
                'Dropdown Menus'
            )),

            // Add our main_module to the parent module (ACP_DEMO_TITLE)
            array('module.add', array(
                'acp',
                'Dropdown Menus',
                array(
                    'module_basename'       => '\halferty\dropdownmenus\acp\main_module',
                    'modes'                         => array('settings'),
                ),
            )),
        );
    }
}
