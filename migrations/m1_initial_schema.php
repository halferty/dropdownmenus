<?php
namespace halferty\dropdownmenus\migrations;

class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	 * {@inheritDoc}
	 */
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'dropdownmenus');
	}

	/**
	 * {@inheritDoc}
	 */
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v32x\v324'];
	}

	/**
	 * Add the dropdownmenus table schema to the database:
	 *	dropdownmenu:
	 *		dropdownmenu_id
	 *		dropdownmenu_title
	 *		dropdownmenu_description
	 *		dropdownmenu_parent
	 *		dropdownmenu_link
	 *		dropdownmenu_position
	 *		dropdownmenu_enabled
	 *
	 * @return array Array of table schema
	 * @access public
	 */
	public function update_schema()
	{
		return array(
			'add_tables'	=> array(
				$this->table_prefix . 'dropdownmenus'	=> array(
					'COLUMNS'	=> array(
						'dropdownmenu_id'				=> array('UINT', null, 'auto_increment'),
						'dropdownmenu_title'			=> array('STEXT_UNI', ''),
						'dropdownmenu_description'		=> array('MTEXT_UNI', ''),
						'dropdownmenu_parent'			=> array('UINT', 0),
						'dropdownmenu_link'			=> array('STEXT_UNI', ''),
						'dropdownmenu_position'		=> array('USINT', 0),
						'dropdownmenu_enabled'			=> array('BOOL', 0)
					),
					'PRIMARY_KEY'				=> 'dropdownmenu_id'
				)
			)
		);
	}

	/**
	 * Drop the dropdownmenus table schema from the database
	 *
	 * @return array Array of table schema
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'dropdownmenus',
			)
		);
	}
}
