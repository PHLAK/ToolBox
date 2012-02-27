<?php

/**
 * ToolBox is a PHP class featuring miscellaneous useful functions.
 * 
 * This software is liscensed under the MIT License. 
 * More info available at https://github.com/PHLAK/ToolBox
 * 
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2011 Chris Kankiewicz
 */
class ToolBox {
    
    /**
     * Generate a random string of characters with a specified length and complexity.
     * 
     * @param int $length Length of desired salt
     * @param bool $strict If true, no character will be repeated
     * @param string|array $charset A string of characters to be used in generating the salt or an
     *                              array of pre-defined sets (lower, upper, num, special, extra)
     * @return string String of specified length and complexity
     * @access public
     */
    public function getRandomString($length, $strict = false, $charset = NULL) {
        
        // Define character sets
        $lowerAlpha = 'abcdefghijklmnopqrstuvwxyz';
        $upperAlpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeric    = '0123456789';
        $special    = '!@#$%^&*()-_=+.?';
        $extra      = '{}[]<>:;/\|~';
        
        $allChars   = $lowerAlpha . $upperAlpha . $numeric . $special . $extra;
        
        // Define possible characters
        if (is_string($charset)) {
            
            // Set the characterset to the user defined string
            $chars = $charset;
            
        } elseif (is_array($charset)) {
            
            // Create empty string
            $chars = NULL;

            // Lower case alpha characters
            if ( in_array('lower', $charset) ) {
                $chars .= $lowerAlpha;
            }
            
            // Upper case alpha characters
            if ( in_array('upper', $charset) ) {
                $chars .= $upperAlpha;
            }
            
            // All numbers
            if ( in_array('num', $charset) ) {
                $chars .= $numeric;
            }
            
            // Special characters
            if ( in_array('special', $charset) ) {
                $chars .= $special;
            }
            
            // Uncommon extra characters
            if ( in_array('extra', $charset) ) {
                $chars .= $extra;
            }
            
        } else {
            
            // All possible characters
            $chars = $allChars;
            
        }
        
        // If strict is set and number of characters is less than desired length, die with error
        if ($strict == true && strlen($chars) < $length) {
            die("ERROR: Available character set is smaller than length.");
        }
        
        // Create empty string
        $salt = NULL;
        
        // Pick a random char and append it to $salt until string length == $length
        $i = 0;
        while ($i < $length) {
            
            // Pick a random character from the pool of available chars
            $char = substr($chars, mt_rand(0, strlen($chars)-1), 1);
            
            // Append the character to $salt if not already used
            if ($strict == true) {
                if (!strstr($salt, $char)) {
                    $salt = $salt.$char;
                    $i++;
                }
            } else {
                $salt = $salt.$char;
                $i++;
            }
            
        }
        
        // Return the salt
        return $salt;
        
    }


    /**
     * Compares two path strings and returns the relative path from one to the other
     * 
     * @param string $fromPath Starting path
     * @param string $toPath Ending path
     * @return string $relativePath
     * @access public
     */
    public function getRelativePath($fromPath, $toPath) {
        
        // Define the OS specific directory separator
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        
        // Remove double slashes from path strings
        $fromPath   = str_replace(DS . DS, DS, $fromPath);
        $toPath     = str_replace(DS . DS, DS, $toPath);
        
        // Explode working dir and cache dir into arrays
        $fromPathArray  = explode(DS, $fromPath);
        $toPathArray    = explode(DS, $toPath);
        
        // Remove last fromPath array element if it's empty
        $x = count($fromPathArray) - 1;
        
        if(!trim($fromPathArray[$x])) {
            array_pop($fromPathArray);
        }
        
        // Remove last toPath array element if it's empty
        $x = count($toPathArray) - 1;
        
        if(!trim($toPathArray[$x])) {
            array_pop($toPathArray);
        }
        
        // Get largest array count
        $arrayMax = max(count($fromPathArray), count($toPathArray));
        
        // Set some default variables
        $diffArray  = array();
        $samePath   = true;
        $key        = 1;
        
        // Generate array of the path differences
        while ($key <= $arrayMax) {
            if (@$toPathArray[$key] !== @$fromPathArray[$key] || $samePath !== true) {
                
                // Prepend '..' for every level up that must be traversed
                if (isset($fromPathArray[$key])) {
                    array_unshift($diffArray, '..');
                }
                
                // Append directory name for every directory that must be traversed  
                if (isset($toPathArray[$key])) {
                    $diffArray[] = $toPathArray[$key];
                } 
                
                // Directory paths have diverged
                $samePath = false;
            }
            
            // Increment key
            $key++;
        }

        // Set the relative thumbnail directory path
        $relativePath = implode('/', $diffArray);
        
        // Return the relative path
        return $relativePath;
        
    }

    /**
     * Return the users connecting IP address.
     * 
     * @return string Connecting IP address
     * @access public
     */
    public function getRemoteAddress() {
        
        // Return the users connecting IP address
        return $_SERVER['REMOTE_ADDR'];
        
    }
    
}


?>