jQuery(document).ready(function() {
   jQuery("#dealerDropDown").close(function() {
        //alert("change");
        var name = jQuery(this).find("option:selected").text();
        var level = jQuery(this).find("option:selected").val();
        jQuery.ajax({
            url:"/ajax/name_changer",
            data:({Agency:name,Level:level}),
            method:"post",
            success:function(data) {
                //alert(data);
                $("#clientInformation").html(data);
                //location.reload();
            }
        }); 
    });
})

