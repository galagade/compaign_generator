<?php

use Illuminate\Database\Seeder;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Dwij\Laraadmin\Models\ModuleFieldTypes;
use Dwij\Laraadmin\Models\Menu;
use Dwij\Laraadmin\Models\LAConfigs;

use App\Role;
use App\Permission;
use App\Models\Department;
class ThemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Generating Module Menus
		$modules = Module::all();
		$teamMenu = Menu::create([
			"name" => "Team",
			"url" => "#",
			"icon" => "fa-group",
			"type" => 'custom',
			"parent" => 0,
			"hierarchy" => 1
		]);
		foreach ($modules as $module) {
			$parent = 0;
			if($module->name != "Backups") {
				if(in_array($module->name, ["Users", "Departments", "Employees", "Roles", "Permissions"])) {
					$parent = $teamMenu->id;
				}
				Menu::create([
					"name" => $module->name,
					"url" => $module->name_db,
					"icon" => $module->fa_icon,
					"type" => 'module',
					"parent" => $parent
				]);
			}
		}

		
		
		// Create Administration Department
	   	$dept = new Department;
		$dept->name = "Administration";
		$dept->tags = "[]";
		$dept->color = "#000";
		$dept->save();

		$dept_client = new Department;
		$dept_client->name = "Clients";
		$dept_client->tags = "[]";
		$dept_client->color = "#507BF3";
		$dept_client->save();
		
		// Create Super Admin Role
		$role = new Role;
		$role->name = "SUPER_ADMIN";
		$role->display_name = "Super Admin";
		$role->description = "Full Access Role";
		$role->parent = 1;
		$role->dept = $dept->id;
		$role->save();

		$role_client = new Role;
		$role_client->name = "CLIENT_USER";
		$role_client->display_name = "Client";
		$role_client->description = "Client access";
		$role_client->parent = 1;
		$role_client->dept = $dept_client->id;
		$role_client->save();

		
		// Set Full Access For Super Admin Role
		foreach ($modules as $module) {
			Module::setDefaultRoleAccess($module->id, $role->id, "full");
		}
		
		// Create Admin Panel Permission
		$perm = new Permission;
		$perm->name = "ADMIN_PANEL";
		$perm->display_name = "Admin Panel";
		$perm->description = "Admin Panel Permission";
		$perm->save();
		
		$role->attachPermission($perm);


		$perm_clnt = new Permission;
		$perm_clnt->name = "CLIENT_PANEL";
		$perm_clnt->display_name = "Clinet";
		$perm_clnt->description = "Client Panel Permission";
		$perm_clnt->save();
		
		$role_client->attachPermission($perm_clnt);
		
		// Generate LaraAdmin Default Configurations
		
		$laconfig = new LAConfigs;
		$laconfig->key = "sitename";
		$laconfig->value = "NODE";
		$laconfig->save();

		$laconfig = new LAConfigs;
		$laconfig->key = "sitename_part1";
		$laconfig->value = "NODE";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "sitename_part2";
		$laconfig->value = "Backoffice";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "sitename_short";
		$laconfig->value = "ND";
		$laconfig->save();

		$laconfig = new LAConfigs;
		$laconfig->key = "site_description";
		$laconfig->value = "NODE is an online campain management CMS systems.";
		$laconfig->save();

		// Display Configurations
		
		$laconfig = new LAConfigs;
		$laconfig->key = "sidebar_search";
		$laconfig->value = "1";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "show_messages";
		$laconfig->value = "1";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "show_notifications";
		$laconfig->value = "1";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "show_tasks";
		$laconfig->value = "1";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "show_rightsidebar";
		$laconfig->value = "1";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "skin";
		$laconfig->value = "skin-white";
		$laconfig->save();
		
		$laconfig = new LAConfigs;
		$laconfig->key = "layout";
		$laconfig->value = "fixed";
		$laconfig->save();

		// Admin Configurations

		$laconfig = new LAConfigs;
		$laconfig->key = "default_email";
		$laconfig->value = "galagadea@gmail.com";
		$laconfig->save();
		
		$modules = Module::all();
		foreach ($modules as $module) {
			$module->is_gen=true;
			$module->save();	
		}

		Menu::create([
			"name" => 'Campaign',
			"url" => 'campaign',
			"icon" => 'fa-group',
			"type" => 'module',
			"parent" => 0
		]);

		$cap = Module::create([
			"name" => 'Campaign',
			"Label" => 'Campaigns',
			"fa_icon" => 'fa-group',
			"name_db" => 'campaigns',
			"view_col" => 'name',
			"model" => 'Campaign',
			"controller" => 'CampaignController',
			"model" => 'Campaign',
			"is_gen" => 1
		]);

		Module::setDefaultRoleAccess($cap->id, $role->id, "full");
		Module::setDefaultRoleAccess($cap->id, $role_client->id, "full");

		 DB::table('module_fields')->insert([
        	[
        	"colname" => 'name',
			"Label" => 'Name',
			"module" => $cap->id,
			"field_type" => 16,
			"unique" => 1,
			"defaultvalue" => '',
			"minlength" => 5,
			"maxlength" => 50,
			"required" => 1,
			"popup_vals" => NULL,
			"sort" => 0
            ],
            [
        	"colname" => 'description',
			"Label" => 'Description',
			"module" => $cap->id,
			"field_type" => 21,
			"unique" => 0,
			"defaultvalue" => '',
			"minlength" => 0,
			"maxlength" => 1000,
			"required" => 1,
			"popup_vals" => NULL,
			"sort" => 0
            ],
            [
        	"colname" => 'questions',
			"Label" => 'Number of questions',
			"module" => $cap->id,
			"field_type" => 13,
			"unique" => 0,
			"defaultvalue" => '',
			"minlength" => 0,
			"maxlength" => 20,
			"required" => 1,
			"popup_vals" => NULL,
			"sort" => 0
            ]

        ]);
    }
}
