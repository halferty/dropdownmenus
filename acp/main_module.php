<?php

namespace halferty\dropdownmenus\acp;

class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    public function main($id, $mode)
    {
        global $template, $request, $config, $phpbb_container;

        $handler = $phpbb_container->get('halferty.dropdownmenus.classes.handler');

        $top_level_menus = $handler->get_dropdownmenus(false);

        $formhtml = '';

        foreach ($top_level_menus as $menu) {
            $formhtml .= '<table class="table table-bordered table-striped">';
            $formhtml .= '<tr><td>';
            $formhtml .= '<input type="hidden" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_id" value="' . $menu['dropdownmenu_id'] . '">';
            $formhtml .= '<label>Title:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_title" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_title" value="' . $menu['dropdownmenu_title'] . '">';
            $formhtml .= '</label><label>Description:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_description" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_description" value="' . $menu['dropdownmenu_description'] . '">';
            $formhtml .= '</label><label>Link:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_link" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_link" value="' . $menu['dropdownmenu_link'] . '">';
            $formhtml .= '</label><label>Position:';
            $formhtml .= '<select class="form-control" id="dropdownmenu_position" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_position">';
            for ($i = 1; $i <= 10; $i++) {
                $formhtml .= '<option value="' . $i . '"' . ($menu['dropdownmenu_position'] == $i ? ' selected' : '') . '>' . $i . '</option>';
            }
            $formhtml .= '</select>';
            $formhtml .= '</label><label>Enabled:';
            $formhtml .= '<input type="checkbox" id="dropdownmenu_enabled" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__dropdownmenu_enabled" value="1" ' . ($menu['dropdownmenu_enabled'] ? 'checked' : '') . '>';
            $formhtml .= '</label>';
            $formhtml .= '<input type="submit" data-action="save-changes-toplevel-' . $menu['dropdownmenu_id'] . '" class="btn btn-primary admin-dropdownmenus-submit" name="submit" value="Save Changes">';
            $formhtml .= '<input type="submit" data-action="delete-toplevel-' . $menu['dropdownmenu_id'] . '" class="btn btn-primary admin-dropdownmenus-submit admin-dropdownmenus-delete" name="submit" value="Delete">';
            $formhtml .= '<h3>Dropdown Items:</h3>';
            $formhtml .= '<table class="table table-bordered table-striped">';
            foreach ($menu['children'] as $child) {
                $formhtml .= '<tr><td>';
                $formhtml .= '<input type="hidden" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_id" value="' . $child['dropdownmenu_id'] . '">';
                $formhtml .= '<label>Title:';
                $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_title" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_title" value="' . $child['dropdownmenu_title'] . '">';
                $formhtml .= '</label><label>Description:';
                $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_description" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_description" value="' . $child['dropdownmenu_description'] . '">';
                $formhtml .= '</label><label>Link:';
                $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_link" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_link" value="' . $child['dropdownmenu_link'] . '">';
                $formhtml .= '</label><label>Position:';
                $formhtml .= '<select class="form-control" id="dropdownmenu_position" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_position">';
                for ($i = 1; $i <= 10; $i++) {
                    $formhtml .= '<option value="' . $i . '"' . ($child['dropdownmenu_position'] == $i ? ' selected' : '') . '>' . $i . '</option>';
                }
                $formhtml .= '</select>';
                $formhtml .= '</label><label>Enabled:';
                $formhtml .= '<input type="checkbox" id="dropdownmenu_enabled" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__' . $child['dropdownmenu_id'] . '__dropdownmenu_enabled" value="1" ' . ($child['dropdownmenu_enabled'] ? 'checked' : '') . '>';
                $formhtml .= '</label>';
                $formhtml .= '<input type="submit" data-action="save-changes-child-' . $menu['dropdownmenu_id'] . '-' . $child['dropdownmenu_id'] . '" class="btn btn-primary admin-dropdownmenus-submit" name="submit" value="Save Changes">';
                $formhtml .= '<input type="submit" data-action="delete-child-' . $child['dropdownmenu_id'] . '" class="btn btn-primary admin-dropdownmenus-submit admin-dropdownmenus-delete" name="submit" value="Delete">';
                $formhtml .= '</td></tr>';
            }
            $formhtml .= '<tr><td>';
            $formhtml .= '<p>Add New Dropdown Item:</p>';
            $formhtml .= '<label>Title:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_title" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__new__dropdownmenu_title">';
            $formhtml .= '</label><label>Description:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_description" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__new__dropdownmenu_description">';
            $formhtml .= '</label><label>Link:';
            $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_link" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__new__dropdownmenu_link">';
            $formhtml .= '</label><label>Position:';
            $formhtml .= '<select class="form-control" id="dropdownmenu_position" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__new__dropdownmenu_position">';
            for ($i = 1; $i <= 10; $i++) {
                $formhtml .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $formhtml .= '</select>';
            $formhtml .= '</label><label>Enabled:';
            $formhtml .= '<input type="checkbox" id="dropdownmenu_enabled" name="top_level_menus__' . $menu['dropdownmenu_id'] . '__children__new__dropdownmenu_enabled" value="1" checked>';
            $formhtml .= '</label>';
            $formhtml .= '<input type="submit" data-action="save-new-child-' . $menu['dropdownmenu_id'] . '" class="btn btn-primary admin-dropdownmenus-submit" name="submit" value="Add New Dropdown Item">';
            $formhtml .= '</td></tr>';
            $formhtml .= '</table>';
            $formhtml .= '</td></tr>';
            $formhtml .= '</table><br>';
        }
        $formhtml .= '<table class="table table-bordered table-striped">';
        $formhtml .= '<tr><td>';
        $formhtml .= '<p>Add New Dropdown Menu:</p>';
        $formhtml .= '<label>Title:';
        $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_title" name="top_level_menus__new__dropdownmenu_title">';
        $formhtml .= '</label><label>Description:';
        $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_description" name="top_level_menus__new__dropdownmenu_description">';
        $formhtml .= '</label><label>Link:';
        $formhtml .= '<input type="text" class="form-control" id="dropdownmenu_link" name="top_level_menus__new__dropdownmenu_link">';
        $formhtml .= '</label><label>Position:';
        $formhtml .= '<select class="form-control" id="dropdownmenu_position" name="top_level_menus__new__dropdownmenu_position">';
        for ($i = 1; $i <= 10; $i++) {
            $formhtml .= '<option value="' . $i . '">' . $i . '</option>';
        }
        $formhtml .= '</select>';
        $formhtml .= '</label><label>Enabled:';
        $formhtml .= '<input type="checkbox" id="dropdownmenu_enabled" name="top_level_menus__new__dropdownmenu_enabled" value="1" checked>';
        $formhtml .= '</label>';
        $formhtml .= '<input type="submit" data-action="save-new-toplevel" class="btn btn-primary admin-dropdownmenus-submit" name="submit" value="Add New Menu">';
        $formhtml .= '</td></tr>';
        $formhtml .= '</table>';

        $this->tpl_name = 'halferty_dropdownmenus_body';
        $this->page_title = 'Dropdown Menus';

        add_form_key('halferty_dropdownmenus_settings');

        if ($request->is_set_post('submit'))
        {
            $values = array();
            foreach ($request->variable_names(\phpbb\request\request_interface::POST) as $key)
            {
                $values[$key] = $request->variable($key, '');
            }
            if (!check_form_key('halferty_dropdownmenus_settings'))
            {
                 trigger_error('FORM_INVALID');
            }
            if ($values['data-action'] === 'save-new-toplevel') {
                $prefix = 'top_level_menus__new__dropdownmenu_';
                $title = $request->variable($prefix . 'title', '');
                $description = $request->variable($prefix . 'description', '');
                $link = $request->variable($prefix . 'link', '');
                $position = $request->variable($prefix . 'position', 1);
                $enabled = $request->variable($prefix . 'enabled', 0);
                $handler->create_dropdownmenu($title, $description, $link, $position, $enabled);
            } elseif (str_starts_with($values['data-action'], 'save-changes-toplevel-')) {
                $toplevelid = intval(explode('save-changes-toplevel-', $values['data-action'])[1]);
                $prefix = 'top_level_menus__' . $toplevelid . '__dropdownmenu_';
                $title = $request->variable($prefix . 'title', '');
                $description = $request->variable($prefix . 'description', '');
                $link = $request->variable($prefix . 'link', '');
                $position = $request->variable($prefix . 'position', 1);
                $enabled = $request->variable($prefix . 'enabled', 0);
                $handler->update_dropdownmenu($toplevelid, $title, $description, $link, $position, $enabled);
            } elseif (str_starts_with($values['data-action'], 'save-new-child-')) {
                $toplevelid = intval(explode('save-new-child-', $values['data-action'])[1]);
                $prefix = 'top_level_menus__' . $toplevelid . '__children__new__dropdownmenu_';
                $title = $request->variable($prefix . 'title', '');
                $description = $request->variable($prefix . 'description', '');
                $link = $request->variable($prefix . 'link', '');
                $position = $request->variable($prefix . 'position', 1);
                $enabled = $request->variable($prefix . 'enabled', 0);
                $handler->create_dropdownitem($toplevelid, $title, $description, $link, $position, $enabled);
            } elseif (str_starts_with($values['data-action'], 'save-changes-child-')) {
                $broken_apart = explode('save-changes-child-', $values['data-action']);
                $broken_apart = explode('-', $broken_apart[1]);
                $toplevelid = intval($broken_apart[0]);
                $childid = intval($broken_apart[1]);
                $prefix = 'top_level_menus__' . $toplevelid . '__children__' . $childid . '__dropdownmenu_';
                $title = $request->variable($prefix . 'title', '');
                $description = $request->variable($prefix . 'description', '');
                $link = $request->variable($prefix . 'link', '');
                $position = $request->variable($prefix . 'position', 1);
                $enabled = $request->variable($prefix . 'enabled', 0);
                $handler->update_dropdownitem($childid, $title, $description, $link, $position, $enabled);
            } elseif (str_starts_with($values['data-action'], 'delete-toplevel-')) {
                $toplevelid = intval(explode('delete-toplevel-', $values['data-action'])[1]);
                $handler->delete_dropdownmenu($toplevelid);
            } elseif (str_starts_with($values['data-action'], 'delete-child-')) {
                $childid = intval(explode('delete-child-', $values['data-action'])[1]);
                $handler->delete_dropdownmenu($childid);
            }
            trigger_error('Dropdown menu settings have been saved successfully!' . adm_back_link($this->u_action));
        }

        $template->assign_vars(array(
            'DROPDOWNMENUS_FORM' => $formhtml,
            'HALFERTY_DROPDOWNMENUS_GOODBYE' => $config['halferty_dropdownmenus_goodbye'],
            'U_ACTION'          => $this->u_action,
        ));
    }
}
