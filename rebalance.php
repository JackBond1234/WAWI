<head>
<link rel="stylesheet" href="../js/jqui/jquery-ui.css">
<script src="../js/jquery.js"></script>
<script src="../js/jqui/jquery-ui.js"></script>
<style>
    tr:nth-child(even) {
        background-color: #DEDEDE;
    }
    tr:nth-child(odd):not(:first-child) {
        background-color: #F5F5F5;
    }
    td {
        padding: 5px;
    }
    th {
        border-bottom: 1px solid;
        background-color: #EFEFEF;
        padding: 5px;
    }
    table {
        border-collapse: separate;
        border-spacing: 4px;
    }
</style>
<script>

    var triggertypedef = ["After money is spent", "Before distributing funds", "On a specific date", "Periodically"];
    var surplusordeficitdef = ["Surplus", "Deficit", "Either"];

    $(function() {
        var surplusordeficit = $("#dialog-surplusordeficit");
        var whentoperform = $("#dialog-whentoperform");
        var nthunit = $("#dialog-nthunit");
        var usedayofweekorlastday = $("#dialog-usedayofweekorlastday");
        var unittype = $("#dialog-unittype");
        var date = $("#dialog-date");

        var surplus = $("#surplus");
        var surplusanddeficit = $("#surplusanddeficit");
        var deficit = $("#deficit");
        var simple = $("#simple");
        var periodically = $(".periodically");
        var stndrdth = $("#stndrdth");
        var impossibledate = $("#impossibledate");
        var monthly = $(".monthly");

        surplusordeficit.change(checksentenceboxes);
        whentoperform.change(checksentenceboxes);
        nthunit.keyup(checksentenceboxes);
        usedayofweekorlastday.change(checksentenceboxes);
        unittype.change(checksentenceboxes);

        function checksentenceboxes() {
            if (surplusordeficit.val() == 0) {
                surplus.show();
                deficit.hide();
                surplusanddeficit.hide();
            }
            else if (surplusordeficit.val() == 1) {
                surplus.hide();
                deficit.show();
                surplusanddeficit.hide();
            }
            else {
                surplus.show();
                deficit.show();
                surplusanddeficit.show();
            }
            //-----------------
            if (whentoperform.val() == 0 || whentoperform.val() == 1) {
                simple.show();
                date.hide();
                periodically.hide();
            }
            else if (whentoperform.val() == 2) {
                simple.hide();
                date.show();
                periodically.hide();
            }
            else {
                simple.hide();
                date.show();
                periodically.show();
            }
            //-----------------
            if (nthunit.val().slice(-1) == 1 && nthunit.val() != "11") {
                stndrdth.html("st");
            }
            else if (nthunit.val().slice(-1) == 2 && nthunit.val() != "12") {
                stndrdth.html("nd");
            }
            else if (nthunit.val().slice(-1) == 3 && nthunit.val() != "13") {
                stndrdth.html("rd");
            }
            else {
                stndrdth.html("th");
            }
            //-----------------
            if (unittype.val() == 2) {
                monthly.show();
            }
            else {
                monthly.hide();
            }
            //-----------------
            if (((usedayofweekorlastday.val() == 0 || usedayofweekorlastday.val() == 1) && unittype.val() == 2) || unittype.val() == 3) {
                impossibledate.show();
            }
            else {
                impossibledate.hide();
            }
        }

        checksentenceboxes();

        //Initialize Category Settings Dialog Box
        var dialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 450,
            show: {
                effect: "fade",
                duration: 100
            }
        });

        $("#new-rebalance").click(function () {
            dialog.dialog("option", "title", "New Re-Balance Rule");
            dialog.dialog("open");
        });

        $("#rebalance-submit").click(function() {
            var dialog_categoryid = $("#dialog-categoryid");
            var dialog_sendtopullfrom = $("#dialog-sendtopullfrom");
            if (dialog_categoryid.val() != dialog_sendtopullfrom.val()) {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {
                        method: "add_rebalance",
                        catid: dialog_categoryid.val(),
                        surplusordeficit: $("#dialog-surplusordeficit").val(),
                        sendtopullfrom: dialog_sendtopullfrom.val(),
                        whentoperform: $("#dialog-whentoperform").val(),
                        date: $("#dialog-datebox").val(),
                        unittype: $("#dialog-unittype").val(),
                        nthunit: $("#dialog-nthunit").val(),
                        usedayofweekorlastday: $("#dialog-usedayofweekorlastday").val(),
                        impossibledate: $("#dialog-impossibledate").val()
                    },
                    success: function () {
                        dialog.dialog("close");
                        refresh();
                    }
                });
            }
            else
            {
                var selectedcat = dialog_categoryid.children("option[value="+dialog_categoryid.val()+"]").text();
                alert("ERROR: A category may not rebalance from itself. You selected category '"+selectedcat+"' twice.");
            }
        });

        $(document).on("click", ".delete-rebalance", function(){
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {
                        method: "delete_rebalance",
                        rebalanceid: $(this).val()
                    },
                    success: function(data) {
                        console.log(data);
                        refresh();
                    }
                });
            }
        });

        function refresh(){
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "refresh_rebalance"
                },
                success: function (data){
                    var catdropdowns = "";
                    for (var i = 0; i < data.categories.length; i++) {
                        catdropdowns += "<option value='"+data.categories[i].CategoryId+"'>"+data.categories[i].Name+"</option>";
                    }
                    $("#dialog-categoryid").html(catdropdowns);
                    $("#dialog-sendtopullfrom").html("<option value='NULL'>Unallocated</option>" + catdropdowns);

                    var tablecontent = "<tr><th>Category Name</th>";
                    tablecontent += "<th>Send To/Pull From</th>";
                    tablecontent += "<th>Trigger Type</th>";
                    tablecontent += "<th>Surplus/Deficit</th>";
                    tablecontent += "<th>Today?</th>";
                    tablecontent += "<th>Delete</th></tr>";

                    for (var i = 0; i < data.rebalances.length; i++) {
                        tablecontent += "<tr>";
                        tablecontent += "<td>"+data.rebalances[i].CategoryName+"</td>";
                        tablecontent += "<td>"+data.rebalances[i].SendToPullFromName+"</td>";
                        tablecontent += "<td>"+triggertypedef[data.rebalances[i].TriggerType]+"</td>";
                        tablecontent += "<td>"+surplusordeficitdef[data.rebalances[i].SurplusDeficit]+"</td>";
                        if (data.rebalances[i].TriggerType > 1) {
                            tablecontent += "<td>" + data.rebalances[i].Today + "</td>";
                        }
                        else
                        {
                            tablecontent += "<td>N/A</td>";
                        }
                        tablecontent += "<td><button value='"+data.rebalances[i].RebalanceId+"' class='delete-rebalance'>Delete</button></td>";
                        tablecontent += "</tr>";
                    }
                    $("#rebalance-table").html(tablecontent);
                }
            });
        }

        refresh();
    });
</script>
</head>
<body>

<a href="indexOld.php">Home</a>
<br>
<button id="new-rebalance">New Re-Balance Rule</button>


<div id="dialog-form" title="Category" style="text-align:center;">
For category
<select id='dialog-categoryid'>
    <option>Loading...</option>
</select>
when there is a
<select id="dialog-surplusordeficit">
    <option value="0">surplus</option>
    <option value="1">deficit</option>
    <option value="2">surplus or deficit</option>
</select>,
<span id="surplus">
send the surplus amount to
</span>
<span id="surplusanddeficit"> or </span>
<span id="deficit">
refill the deficit by pulling from
</span>
<select id="dialog-sendtopullfrom">
    <option>Loading...</option>
</select>.
Perform this operation
<select id="dialog-whentoperform">
<option value="0">after money is spent</option>
<option value="1">before distributing funds</option>
<option value="2">on a specific date</option>
<option value="3">periodically</option>
</select><span id="simple">.</span>
<span class="periodically">
starting on
</span>
<span id="dialog-date">
    <input id="dialog-datebox" type="text"/>
</span>
<span class="periodically">
and on every <input id="dialog-nthunit" type="text"/><span id="stndrdth">th</span>
    <select id="dialog-unittype">
        <option value="0">day</option>
        <option value="1">week</option>
        <option value="2">month</option>
        <option value="3">year</option>
    </select>
afterward<span class="monthly">
    <select id="dialog-usedayofweekorlastday">
        <option value="0">on the same day of the month as the start</option>
        <option value="1">on the same week and day of week as the start</option>
        <option value="2">on the last day of the month</option>
    </select></span>.
<span id="impossibledate" class="monthly">
If the day does not exist,
        <select id="dialog-impossibledate">
            <option value="0">perform the operation at the end of the month instead</option>
            <option value="1">do not perform the operation</option>
        </select>.
    </span>
</span>
<div><button id="rebalance-submit">Submit</button></div>
</div>

<table id="rebalance-table">

</table>

</body>