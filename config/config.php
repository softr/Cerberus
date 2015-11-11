<?php

//---------------------------------------------
// Access Control Layer Configuration
//---------------------------------------------

return
[
    /**
     * Set the super users ids wich has access to all rules
     *
     * @var array
     */

    'superusers' => [], // Eg.: [1,2,3]


    /**
     * Set a custom rules file path. Default is app/rules.php
     *
     * @var array
     */
    'rules_file' => null,
];