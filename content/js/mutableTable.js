
var $ = jQuery.noConflict();

function mutableTableCellStartEdit(id) {
	node = $("#"+id);
	// Create an input box for the cell.
	input = '<input id="mutableTableCellInput" value="' + node.attr('value') + '">';
	// Insert into cell.
	node.html(input);
}

$(".mutable-table-cell.editable").click(function() {
	var padding = $(this).css('padding');
	$(this).css('padding','0');
	var cellid = $(this).attr('id');
	// Create an input box.
	var style = 'background-color:yellow;height:100%;width:100%;border:none;padding:0';
	var input = '<textarea class="mutable-table-editor" cellID="' + cellid + '" cellPadding="' + padding + '" style="' + style + '" type="text">' + $(this).text() + '</textarea>';
	// Insert into box
	$(this).html(input);
	$(".mutable-table-editor").select();
	$(".mutable-table-editor").on("blur", function(event) {
		var cellid = $(this).attr('cellID');
		var cell = document.getElementById(cellid);
		var value = $(this).val();
		cell.style.padding = $(this).attr('cellPadding');
		// Remove edit box.
		$(this).remove();
		// Save data.
		cell.innerHTML = value;
	});
	$(".mutable-table-editor").on("keydown", function(event) {
		
	});
	$(".mutable-table-editor").focus();
});