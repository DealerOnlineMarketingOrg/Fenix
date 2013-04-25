<?php

/**
 * simple string of elements passed from the database in var:int,var:int format
 * $required is a comma-delimited list of required keys. If key is not found,
 *   creates one with an empty string.
 * $allowDuplicates parses out the string if there's potential duplicates in
 *   key names. Format of returned data for allowDuplicates is a number-indexed
 *   array in the form:
 *   [0] { key = 'keyName', value = 'value' }
 * @param	string
 */
function mod_parser($str, $required = FALSE, $allowDuplicates = FALSE) {
    //expload string into array and split on comma
    $str = explode(',', $str);

    //empty array to return
    $simple = array();
    //iterate through array and assign var name (before colon) as the key and the int(after colon) as value
    foreach ($str as $item) {
        //expload the array values and create an array with keys that make sense to the app
        $mod = explode(':', $item);
		if (!$allowDuplicates)
	        $simple[trim($mod[0])] = (isset($mod[1])) ? trim($mod[1]) : '';
		else
	        $simple[][trim($mod[0])] = (isset($mod[1])) ? trim($mod[1]) : '';
    }
	
	//iterate through the required list and check to make sure all required items exist.
	if ($required) {
		if (!$allowDuplicates) {
			$str = explode(',', $required);
			foreach ($str as $sitem) {
				$s = trim($sitem);
				// If item doesn't exist yet..
				if (!array_key_exists($s, $simple))
					// ..create it.
					$simple[$s] = '';
			}
		} else {
			$str = explode(',', $required);
			foreach ($str as $sitem) {
				$s = trim($sitem);
				// Locate item if exists.
				$exists = false;
				foreach ($simple as $key => $value)
					if ($key == $s) {
						$exists = true;
						break;
					}
				// If item doesn't exist yet..
				if (!$exists)
					// ..create it.
					$simple[][$s] = '';
			}
		}
	}

    return $simple;
}

function group_parser($str) {
    //these are the delimiters to seperate strings and groups of strings
    $string_separater = ',';
    $group_separater = '|';
    //this is the delimeter to seperate title from value
    $title_separater = ':';
    //expload the string into an array
    $str = explode($group_separater, $str);
    //create an empty array to store return data
    $emails = array();

    //loop through array and pull out data from the strings
    foreach ($str as $item) {
        //create array from comma seperated string
        $parts = explode($string_separater, $item);
        //loop through each of these and pull data
        foreach ($parts as $key => $value) {
            //separate the title from the value. 
            //we use the title as the key for the array
            $keys = explode($title_separater, $value);
            //fill the array
            $group[trim($keys[0])] = trim($keys[1]);
        }
        //push data to fill empty array with our cleaned version
        array_push($emails, $group);
    }
    //return this to the app
    return $emails;
}

function dropdown_parser($DropString) {

    $ci =& get_instance();
    $selected_item = $ci->session->userdata['valid_user']['DropdownDefault']->SelectedID;

    $pattern = '/[|]/';
    $pattern2 = '/[:;^,]/';
    $arr = preg_split($pattern, $DropString);
    $DropStringCode = '';
    for ($i = 0; $i < count($arr); $i++) {
        $value = preg_split($pattern2, $arr[$i]);
        //print_object($value);
        if ($selected_item != NULL AND isset($value[1])) {
            if ($selected_item == $value[1]) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
        } else {
            if (isset($value[4]) AND !$value[4]) :
                $selected = '';
            else:
                $selected = 'selected="selected"';
            endif;
        }
        if (count($value) > 1)
            $DropStringCode .= '<option id="' . $value[1] . '" data-level="' . $value[1] . '"' . $selected . ' value="' . $value[0] . $value[1] . '"' . ' class="' . $value[3] . '">' . $value[2] . '</option>' . "\n";
    }

    //echo $DropString;

    $myDropdown = $DropStringCode;
    return $myDropdown;
}

function client_tag_parser($tag_list_set, $tag_id) {
    $tag_list_set = substr($tag_list_set, 0, -1);
    $pattern = '/[|]/';
    $pattern2 = '/[,]/';
    $arr = preg_split($pattern, $tag_list_set);
    (($tag_id == '0') ? $selected = 'selected="selected"' : $selected = '');
    $TagDropCode = '<select id="tags" class="select" name="tag_dropdown"><option value="0" ' . $selected . '>-none-</option>';
    foreach ($arr as &$value) {
        $value = preg_split($pattern2, $value);
        $selected = '';
        if ($value[3] == 1):
            $selected = 'selected="selected"';
        else:
            $selected = '';
        endif;

        $TagDropCode .= '<option class="' . $value[2] . '" ' . $selected . ' value="' . $value[1] . '">' . $value[0] . '</option>';
        //<span class="height:10px; width:10px; backgorund-color:'.$value[2].'"></span>
    }

    //<option value="'.$tags_ids.'">'.$tag_name.'<div class="tag_color" style="height:5px; width:5px; background-color:'.$tag_color.'; "></div></option>
    $TagDropCode .= '</select>';
    return $TagDropCode;
}

function client_id_parser($c_ids) {
    $c_ids = substr($c_ids, 0, -1);
    $special = explode(",", $c_ids);
    return $special;
}

?>
