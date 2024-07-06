<?php

namespace App\Http\Controllers;

abstract class Controller
{
    function fixJsonString($json)
{
// Regular expression to match keys and unquoted string values
$pattern = '/"([^"]+)"\s*:\s*([^",\s{}\[\]]+)/';

// Callback function to add quotes around string values
$callback = function($matches) {
    // Add quotes around the value if it's not already quoted and not a number
    if (preg_match('/^-?\d+\.?\d*$/', $matches[2]) === 0) {
        return '"' . $matches[1] . '":"' . $matches[2] . '"';
    } else {
        return '"' . $matches[1] . '":' . $matches[2];
    }
};

// Perform the replacement
$fixedJson = preg_replace_callback($pattern, $callback, $json);

return $fixedJson;
}

}
