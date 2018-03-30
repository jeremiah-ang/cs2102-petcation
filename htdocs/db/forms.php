<?php
include_once __DIR__ . "/../script/required.php";
# [label, tagname, type, name, value, [attributes*]]
# all will be converted to lowercaps
$PRIMARY_KEYS = [
	"users" => ['userid'],
	"pets" => ['petid', 'userid'],
	"bids" => ['buyerid', 'petid', 'sellerid', 'serviceid'],
	"trans" => ['bidid', 'buyerid'],
	"wallet" => ['topupid', 'userid'],
	"winners" => ['buyerid', 'petid', 'sellerid', 'serviceid'],
	"service" => ['serviceid', 'userid'],
	"stats" => []
];

$FORMS = [
	"users" => [
		["User Id", "input", "text", "userid", "", ['required']],
		["Password", "input", "password", "password", "", ['required']],
		["Full Name", "input", "text", "username", "", ['required']],
		["Date Of Birth", "input", "date", "dateofbirth", "", ['required']],
		["Address", "textarea", "text", "address", "", ['required']]
	],
	"pets" => [
		["Pet Id", "input", "hidden", "petid", "petid", ["readonly = 'readonly'", 'required']],
		["Owner User Id", "input", "text", "userid", "userid", ["readonly = 'readonly'", 'required']],
		["Name", "input", "text", "petname", "", ['required']],
		["Type", "select", "text", "pettype", "pettype", ['required']]
	],
	"bids" => [
		["Buyer Id", "input", "text", "3buyerid", "buyerid", ["readonly = 'readonly'", 'required']],
		["Seller Id", "input", "text", "1sellerid", "sellerid", ["readonly = 'readonly'", 'required']],
		["Service Id", "input", "text", "2serviceid", "serviceid", ["readonly = 'readonly'", 'required']],

		["Start Date", "input", "date", "_startdate", "_startdate",["readonly = 'readonly'",'required']],
		["End Date", "input", "date", "_enddate", "_enddate",["readonly = 'readonly'",'required']],
		["Pet Type", "input", "text", "_pettype", "_pettype",["readonly = 'readonly'",'required']],
		["Description", "textarea", "text", "_description", "_description",["readonly = 'readonly'",'required']],

		["Pet Id", "select", "text", "4petid", "petids", ['required']],
		["Amount", "input", "number", "5amount", "amount", ["min = '1'", 'required']]
	],
	"trans" => [
		["Trans Id", "input", "hidden", "bidid", "", ['required']],
		["User Id", "input", "text", "buyerid", "", ['required']],
		["Trans Date", "input", "text", "petid", "DEFAULT", ['required']],
		["Trans Type", "input", "text", "sellerid", "", ['required']],
		["Trans Amount", "input", "text", "serviceid", "", ['required']]
	],
	"wallet" => [
		["User Id", "input", "text", "1userid", "userid", ["readonly = 'readonly'", 'required']],
		["Amount", "input", "text", "2amount", "", ['required']]
	],
	"service" => [
		["Service Id", "input", "hidden", "serviceid", "serviceid", ["readonly = 'readonly'", 'required']],
		["Seller Id", "input", "text", "userid", "userid", ["readonly = 'readonly'", 'required']],
		["Start Date", "input", "date", "startdate", "", ['required']],
		["End Date", "input", "date", "enddate", "", ['required']],
		["Pet Type Allowed", "select", "text", "pettype", "pettype", ['required']],
		["Description", "textarea", "text", "description", "", ['required']]
	]
];

function CREATE_TABLE ($crud, $tablename, $values=[], $attrs=[], $method="POST", $formname=NULL, $action=NULL) {
	global $FORMS;
	$fieldsOption = $FORMS[$tablename];

	$hidden = "";
	$fields = "";
	foreach ($fieldsOption as $options) {
		$label = $options[0];
		$tagname = $options[1];
		$type = $options[2];
		$name = $options[3];
		$value = set_value(($options[4] === "") ? ($crud === "create") ? "" : $name : $options[4], $values);
		$createAttr = (isset($options[5])) ? $options[5] : [];
		$updateAttr = (isset($options[6])) ? $options[6] : [];

		$attr = make_attr($crud, $tablename, $name, $createAttr, $updateAttr, $attrs);

		$field = make_field($tagname, $type, $name, $value, $attr);
		if ($type === "hidden") {
			$hidden .= $field;
		} else {
			lilabel ($label, $field);
			$fields .= $field;
		}
		
	}
	ul($fields);
	make_form($fields, $hidden, $crud, $tablename, $method, $formname, $action);

	return $fields;
}
function CREATE_UPDATE_TABLE ($post, $query, $crud, $tablename, $options=[]) {
	if (has_update_key($post)) {
		$key = get_update_key($post);
		$row = retrieve_row ($query, $key);
		foreach ($row as $key=>$value) {
			if (isset($options[$key])) {
				foreach ($options[$key] as $optionkey=>$option) {
					if ($option[0] === $value) {
						$options[$key][$optionkey][] = TRUE;
					}
				}
				$row[$key] = $options[$key];
			}
		}

		echo CREATE_TABLE($crud, $tablename, $row);
	}
}

function make_attr ($crud, $tablename, $name, $createAttr, $updateAttr, $attrs) {
	global $PRIMARY_KEYS;
	if (in_array($name, $PRIMARY_KEYS[$tablename])) {
		$updateAttr[] = "readonly = 'readonly'";
	}

	$target = $updateAttr;
	if ($crud === "create") {
		$target = $createAttr;
	}

	if (isset($attrs[$name]))
		$target = array_merge($target, $attrs[$name]);
	return implode(" ", $target);
}

function make_form (&$fields, $hidden, $crud, $tablename, $method, $formname=NULL, $action=NULL) {
	$formname = (is_null($formname)) ? $crud . $tablename : $formname;
	$action = (is_null($action)) ? "" : "action='$action'";
	$form = "<form id='input_form' name='$formname' method='$method' $action onsubmit='return formOnSubmit(this);'> ";
	$form .= "<input type='hidden' name='table' value='$tablename'></input>";
	$form .= $hidden;
	$fields = $form . $fields . "<input type='submit' name='$crud'></input></form>";
}

function make_field ($tagname, $type, $name, $value, $attr) {
	if ($tagname === "textarea") {
		$field = make_textarea ($name, $value, $attr);
	} else if ($tagname === "input") {
		$field = make_input ($name, $value, $type, $attr);
	} else if ($tagname === "select") {
		$field = make_select ($name, $value, $attr);
	} else {
		return "";
	}

	return $field;
}

function lilabel ($label, &$field) {
	$field = "<li><label>$label: </label>$field</li>";
}
function ul (&$lis) {
	$lis = "<ul>$lis</ul>";
}
function set_value ($value, $values) {
	return (count($values) > 0 && isset($values[$value])) ? $values[$value] : $value;
}
function make_textarea ($name, $value, $attr) {
	return "<textarea name='$name' $attr >$value</textarea>";
}
function make_input ($name, $value, $type, $attr) {
	$today = "";
	if ($type == "date") {
		$today = "min = " . date("d/m/Y");
	}
	return "<input type='$type' name='$name' $attr value='$value'></input>";
}
function make_select ($name, $value, $attr) {
	
	$select = "<select $attr name=$name>";
	foreach($value as $row) {
		$selected = "";
		if (isset($row[2]))
			$selected = "selected";
		$select .= "<option value=$row[0] $selected>$row[1]</option>";
	}
	$select .= "</select>";
	return $select;
}
function get_primary_key ($tablename, $json=false) {
	global $PRIMARY_KEYS;
	$key = $PRIMARY_KEYS[$tablename];
	return ($json) ? json_encode($key) : $key;
}
?>