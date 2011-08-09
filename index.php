<?php
    
    // Include ToolBox class
    include 'ToolBox.php';
    
    // Instantiate the object
    $toolBox = new ToolBox();
    
    // Generate a random salt
    $salt = $toolBox->makeSalt(16, true);
    
    // Echo random salt
    echo '<pre>' . htmlentities($salt) . '</pre>';

    
