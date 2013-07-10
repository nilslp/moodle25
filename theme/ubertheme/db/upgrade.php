<?php

defined('MOODLE_INTERNAL') || die();
 
function xmldb_theme_ubertheme_upgrade($oldversion) {
		global $DB,$CFG;
 
		if ($oldversion < 2012091001) {
				
				// - New capability for editing basic theme settings
				$syscontext = get_system_context();
				$role_cap = new stdClass();
				$role_cap->contextid = $syscontext->id;
				$role_cap->roleid = $DB->get_field('role','id',array('shortname'=>'site_admin'));
				$role_cap->permission = 1;
				$role_cap->timemodified = time();
				$role_cap->modifierid = 0;

				echo "Configuring capabilities ...\n";

				// edit blocks
				$role_cap->capability = 'theme/ubertheme:config';
				$exists = $DB->get_field(
								'role_capabilities',
								'id',
								array('capability'=>$role_cap->capability,'contextid'=>$role_cap->contextid,'roleid'=>$role_cap->roleid)
								);
				
				if (!$exists && !$DB->insert_record('role_capabilities',$role_cap)){
						echo "Failed to add capability {$role_cap->capability} for site admin! (Perhaps it already exists?)\n";
				}
				
		}
		
		
		if ($oldversion < 2012070503) {
				
				// instead of overwriting the old configs, just duplicate their values into the new configs
				$dupes = array(
						'roundedcorners'		=> 'pagerounded',
						'shadoweffect'			=> 'pageshadow',
						'usegradients'			=> 'pagegradients',
						'roundedcorners'		=> 'blockrounded',
						'shadoweffect'			=> 'blockshadow',
						'usegradients'			=> 'blockgradient',
						'shadoweffect'			=> 'menushadow',
						'usegradients'			=> 'menugradient', 
						'fixedpagewidth'		=> 'pagefixedwidth',
						'logo'							=> 'logo1'
				);
				
				// using 'set_config' means we don't need to check if the value exists already
				foreach ($dupes as $old => $new) {
						$cfg = get_config('theme_ubertheme', $old);
						if (!empty($cfg)) {
								set_config($new, $cfg, 'theme_ubertheme');
						}
				}
				
				// combine the current welcome message into a new config
				$auth_instructions = get_config(null,'auth_instructions');
				if (!empty($auth_instructions)) {
						$auth_instructions = "<h1>". get_string('firsttime') . "</h1> $auth_instructions";
			set_config('customanonhomepagecontent', $auth_instructions, 'theme_ubertheme');
				}

	if (!empty($CFG->forcelogin) && $CFG->theme == 'ubertheme') {
			set_config('customanonhomepage', '1','theme_ubertheme');
			set_config('forcelogin','0');
	}
				
				upgrade_plugin_savepoint(true, 2012070503, 'theme', 'ubertheme');
		}
 
		return true;
}
