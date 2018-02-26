function createOnSubmit (table) {
	return function (form) {
		// var elements = form.elements;
		// var input = {};
		// for (var i = 0; i < elements.length; i++) {
		// 	input[elements[i].name] = elements[i].value;
		// }
		// delete input[''];
		// keys = Object.keys(input).join(",")
		// values = Object.values(input).join(",")
		// var sql = "INSERT INTO " + table + " (" + keys + ") VALUES (" + values + ")";

		// return sql
		return true;
	}
}