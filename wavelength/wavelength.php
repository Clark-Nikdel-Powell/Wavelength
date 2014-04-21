<?php

/*
    Plugin Name: Wavelength
    Plugin URI: http://clarknikdelpowell.com
    Version: 0.1.0
    Description: Provides additional media filters with automatic mime type detection. Filters all the things!
    Author: Glenn Welser
    Author URI: http://clarknikdelpowell.com/agency/people/glenn/

    Copyright 2014+ Clark/Nikdel/Powell (email : glenn@clarknikdelpowell.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2 (or later),
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

final class CNP_Wavelength {

    public static function activation() {
        /* PLUGIN ACTIVATION LOGIC HERE */
    }

    public static function deactivation() {
        /* PLUGIN DEACTIVATION LOGIC HERE */
    }

    public static function uninstall() {
        /* PLUGIN DELETION LOGIC HERE */
    }

    public static function initialize() {
        // Add Filter Hook
        add_filter( 'post_mime_types', array(__CLASS__, 'modify_post_mime_types') );
    }
    
    public static function modify_post_mime_types( $post_mime_types ) {
        
        global $wpdb;
        $mimetypes = $wpdb->get_results( "SELECT DISTINCT post_mime_type, substring_index(guid,'.',-1) AS ext FROM wp_posts WHERE post_type='attachment' ORDER BY ext" );
        foreach ($mimetypes as $m)
        {
            $mime_type = $m->post_mime_type;
            $mime_ext = $m->ext;
            $mime_name = strtoupper( $mime_ext );
            $post_mime_types[$mime_type] = array( __( $mime_name ), __( 'Manage '.$mime_name.'s' ), _n_noop( $mime_name.' <span class="count">(%s)</span>', $mime_name.'s <span class="count">(%s)</span>' ) );
        }
        return $post_mime_types;
        
    }
    
}

register_activation_hook(__FILE__, array('CNP_Wavelength', 'activation'));
register_deactivation_hook(__FILE__, array('CNP_Wavelength', 'deactivation'));
register_uninstall_hook(__FILE__, array('CNP_Wavelength', 'uninstall'));
CNP_Wavelength::initialize();
