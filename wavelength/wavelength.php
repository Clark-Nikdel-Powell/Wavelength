<?php

/*
    Plugin Name: Wavelength
    Plugin URI: http://clarknikdelpowell.com
    Version: 0.1.0
    Description: Provides additional media filters with automatic mime type detection.
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

    public static function initialize() {
        add_filter( 'post_mime_types', array(__CLASS__, 'modify_post_mime_types') );
    }
    
    public static function modify_post_mime_types( $post_mime_types ) {
        
        global $wpdb;
        $mimetypes = $wpdb->get_results( 
            "
            SELECT post_mime_type, substring_index(guid,'.',-1) AS ext 
            FROM $wpdb->posts 
            WHERE post_type='attachment' ORDER BY ext
            ",
			OBJECT_K
        );
		
        if ($mimetypes == null) {
            return $post_mime_types;
        }
        
        foreach ($mimetypes as $m)
        {
            $mime_type = $m->post_mime_type;
            $mime_ext = strtoupper( $m->ext );
            
            $post_mime_types[$mime_type] = array( 
                __( $mime_ext ), 
                __( 'Manage '.$mime_ext.'s' ), 
                _n_noop( 
                    $mime_ext.' <span class="count">(%s)</span>', 
                    $mime_ext.'s <span class="count">(%s)</span>' 
                )
            );
        }
        return $post_mime_types;
        
    }
    
}

CNP_Wavelength::initialize();
