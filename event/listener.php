<?php

namespace halferty\dropdownmenus\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $phpEx;

    public function __construct(\halferty\dropdownmenus\classes\handler $handler, \phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\request\request_interface $request, \phpbb\template\template $template, \phpbb\user $user, $root_path, $phpEx, $dropdownmenus_table)
    {
        $this->handler = $handler;
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->root_path = $root_path;
		$this->phpEx = $phpEx;
        $this->dropdownmenus_table = $dropdownmenus_table;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header' => 'dropdownmenus',
		);
	}

	public function dropdownmenus()
	{
		global $phpbb_root_path, $cache;
        $top_level_menus = $this->handler->get_dropdownmenus();
		$careticon = $cache->get('dropdownmenus_caret_icon');
		if (!$careticon)
		{
			$careticon = file_get_contents(__DIR__ . '/../styles/all/theme/images/dropdownmenu-caret-icon.svg');
			$cache->put('dropdownmenus_caret_icon', $careticon);
		}
        // Generate HTML for nav header
        $html = '';
        foreach ($top_level_menus as $menu) {
            $html .= '<a data-items="' . base64_encode(json_encode($menu['children'])) . '" title="' . $menu['dropdownmenu_description'] . '" href="' . $menu['dropdownmenu_link'] . '" class="dropdownmenu dropdown-toggle postlink" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-dropdownmenu-id="' . $menu['dropdownmenu_id'] . '"><span>' . $menu['dropdownmenu_title'] . '</span>' . $careticon . '</a>';
        }

        $this->template->assign_vars(array(
            'DROPDOWNMENUS' => $html,
            'DROPDOWNMENUS_ENABLED' => true,
			'DROPDOWNMENUS_CARET_ICON' => $this->root_path . 'ext/halferty/dropdownmenus/styles/all/theme/images/dropdownmenu-caret-icon.png',
        ));
	}
}
