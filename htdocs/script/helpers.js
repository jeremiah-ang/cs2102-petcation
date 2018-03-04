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

function makeRetrievedOnClick (link, ids, params=[]) {
	return function (table) {
		return function (rowno) {
			console.log(rowno);
			while (table != null && table.nodeName !== "TABLE")
				table = table.parentElement;

			var columns = []
			for (var i = 0; i < table.rows[0].cells.length; i++) {
				if (ids.includes(table.rows[0].cells[i].innerHTML.trim())) {
					columns.push(i);
				}
			}

			for (var i = 0; i < columns.length; i++) {
				params.push(encodeURIComponent(table.rows[rowno].cells[columns[i]].innerHTML.trim()));
			}

			var form = document.createElement("form");
			form.action = link;
			form.method = "post";
			form.style.display = "none";
			var submit = document.createElement("input");
			submit.type = "submit";
			submit.name = "update";
			var key = document.createElement("input");
			key.type = "hidden";
			key.name = "key";
			key.value = params.join(",");
			form.appendChild(key);
			form.appendChild(submit);
			document.body.appendChild(form);
			form.submit()
		}
	}
}