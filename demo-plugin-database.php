<?php
/*
 Plugin Name: Demo Plugin Database
 Plugin URI: http://website-in-a-weekend.net/plugins/
 Description: Demo Plugin Database provides the simplest possible class-based example for setting up and dropping a database table in a WordPress plugin.
 Version: 0.1
 Author: Dave Doolin
 Author URI: http://website-in-a-weekend.net/plugins/
 */

/*  Copyright 2009  Dave Doolin  (email : david.doolin@gmail.com)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * This plugin exists only to demonstrate _exactly_ how a database 
 * table is set up and torn down.
 */
if (!class_exists("demo_plugin_database")) {
	
    class demo_plugin_database {

        function demo_plugin_database() {

            add_action('admin_menu', array(&$this,'on_admin_menu'));
            register_activation_hook(__FILE__, array (&$this,'create_table'));
            register_deactivation_hook(__FILE__, array (&$this,'drop_table'));
        }


        function on_admin_menu() {
            add_options_page('Demo Database Page', 'Demo Database', 8, __FILE__ , array ( & $this, 'database_options'));
        }

        function database_options() {
            global $wpdb;
			?>
			<div class="wrap">
				<h2>Demo Database Plugin</h2>
				
					<p>The WIAW Demo Database Plugin provides a very 
                    simple example of a class-based WordPress plugin that 
					creates a database table on activation, and 
					drops the table when deactivated.</p>
			<?php
            $demodata = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."demodatabase");
            echo '<p>id field: '.$demodata->id.'; text field: '.$demodata->text.'</p>';
            ?>		
					<p>For more simple demonstration plugins, check 
					out <a href="http://website-in-a-weekend.net/plugins">Website In A Weekend.</a></p>
			</div>
			<?php
		}
        
        function create_table() {
        
            global $wpdb;
        
            $table_name = $wpdb->prefix."demodatabase";
            if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        
                $sql = "CREATE TABLE ".$table_name." (
        	        id mediumint(9) NOT NULL AUTO_INCREMENT,
        	        text text NOT NULL,
        	        UNIQUE KEY id (id)
        	        );";
        
                require_once (ABSPATH.'wp-admin/includes/upgrade.php');
                dbDelta($sql);        
            }
            
            // Stick something in the database to display on the options page.
            $dbdata = array('id' => 13,
                            'text' => 'Blah blah');
            $wpdb->insert($wpdb->prefix . 'demodatabase', $dbdata);
        }

        /* find a plugin to use for an example for deleting 
         * database on uninstall.
         */
        function drop_table($plugin) {
        	
            global $wpdb;
            $wpdb->query("DROP TABLE {$wpdb->prefix}demodatabase");
        }

    }
}

$wpdpd = new demo_plugin_database();

?>
