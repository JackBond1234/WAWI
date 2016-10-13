var editingcategoryid = null;
var sendcategoryid = null;
var accrueByArray = ["Flat Amount", "Percentage Individual", "Percentage Group", "Automatic"];
$(function() {

    var whentoperform = $("#dialog-whentoperform");
    var nthunit = $("#dialog-nthunit");
    var usedayofweekorlastday = $("#dialog-usedayofweekorlastday");
    var unittype = $("#dialog-unittype");
    var dialogAutoDate = $("#dialog-auto-date");
    var sortable = $("#sortable");
    var todayDateBox = $("#today-date-box");

    var simple = $("#simple");
    var periodically = $(".periodically");
    var stndrdth = $("#stndrdth");
    var impossibledate = $("#impossibledate");
    var monthly = $(".monthly");

    whentoperform.change(function(){checksentenceboxes();updateEstimate();});
    nthunit.keyup(function(){if (event.which == undefined || (event.which >= 48 && event.which <= 57) || event.which == 190 || event.which == 8 || event.which == 46) {checksentenceboxes();updateEstimate();}});
    usedayofweekorlastday.change(function(){checksentenceboxes();updateEstimate();});
    unittype.change(function(){checksentenceboxes();updateEstimate();});

    function checksentenceboxes() {
        if (whentoperform.val() == 0) {
            simple.hide();
            dialogAutoDate.show();
            periodically.hide();
        }
        else {
            simple.hide();
            dialogAutoDate.show();
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

//Setup event handling for category drag and drop
    sortable.sortable({
        update:function(){
            var categories = $("#sortable").children("li:not(.fixed)");
            var categoryChanges = [];
            categories.each(function(index){
                categoryChanges.push({
                    currid: categories.eq(index).val(),
                    upperid: (index > 0 ? categories.eq(index-1).val() : "null"),
                    lowerid: (index < categories.length-1 ? categories.eq(index+1).val() : "null")
                });
            });
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "arrange_categories",
                    categoryChanges: categoryChanges
                },
                success: function(data){
                    console.log(data);
                    refresh();
                }
            });
        }
    });
    sortable.disableSelection();

//Handle Add Category button press
    $("#addcategorybutton").click(function(){
        var categoryName = prompt("Name the new category");
        if (categoryName != null)
        {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "create_category",
                    newcategoryname: categoryName
                },
                success: function(data){
                    console.log(data);
                    refresh();
                },complete: function(data) {console.log(data);}
            })
        }
    });

//Handle Category X button press
    $(document).on("click", ".delete-cat", function(){
        if (confirm("Are you sure you want to delete " + $(this).parent().attr("data-catname") + "?"))
        {
//                    var origthis = $(this);
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "delete_category",
                    deletecat: $(this).parent().val()
                },
                success: function(data){
                    console.log(data);
                    refresh();
                }, complete: function(data){console.log(data);}
            });
        }
    });

//Handle Category arrow send button press
    $(document).on("click", ".cat-send", function(){
        sendcategoryid = $(this).parent().parent().attr("data-category");
        dialogSend.dialog("option", "title", "Transfer from " + $(this).parent().parent().attr("data-catname"));
        dialogSend.dialog("open");
    });

//Handle Send Submit Button Press
    $("#dialog-form-send-submit").click(function(){
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                method: "category_send",
                fromcatid: sendcategoryid,
                tocatid: $("#dialog-send-category").val(),
                amount: $("#dialog-send-amount").val()
            },
            success: function() {
                dialogSend.dialog("close");
                refresh();
            }
        });
    });

//Handle Category gear button press
    $(document).on("click", ".edit-cat", function(){
        if ($(this).parent().parent().attr("data-category") != "NULL") {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "get_category",
                    catid: $(this).parent().parent().attr("data-category")
                },
                /**
                 * @var {array} data
                 * @prop {number} data.Balance
                 * @prop {string} data.Name
                 * @prop {array} data.TargetDate
                 * @prop {int} data.TargetDate.NthUnit
                 * @prop {bool} data.TargetDate.UseDayOfWeek
                 * @prop {bool} data.TargetDate.UseEndOfMonth
                 * @prop {int} data.TargetDate.UnitType
                 * @prop {int} data.TargetDate.ImpossibleDayOfMonthBehavior
                 */
                success: function (data) {
                    editingcategoryid = data.CategoryId;
                    $("#dialog-balance").html("$" + numberWithCommas(data.Balance));
                    var dialogAccrueby = $("#dialog-accrueby");
                    dialogAccrueby.val(data.AccrueBy);
                    if (["1", "2"].indexOf(data.AccrueBy) > -1) {
                        $("#dialog-dollars").hide();
                        $("#dialog-percent").show();
                    }
                    else {
                        $("#dialog-percent").hide();
                        $("#dialog-dollars").show();
                    }
                    if (["0", "1", "2"].indexOf(dialogAccrueby.val()) > -1) {
                        $(".dialog-accby-lt3").show();
                        $(".dialog-accby-eq3").hide();
                    }
                    else {
                        $(".dialog-accby-lt3").hide();
                        $(".dialog-accby-eq3").show();
                    }
                    $("#dialog-accrualamount").val(data.AccrualAmount);
                    $("#dialog-auto-target").val(data.AccrualAmount);
                    if (data.AccrueBy == 3) {
                        if (data.TargetDate.Date > 0) {
                            $("#dialog-auto-date").val(new Date(data.TargetDate.Date * 1000).format("m/d/Y"));
                        }
                        else {
                            $("#dialog-auto-date").val("");
                        }
                        if (data.TargetDate.NthUnit == 0) {
                            $("#dialog-whentoperform").val(0);
                        }
                        else {
                            $("#dialog-whentoperform").val(1);
                        }
                        $("#dialog-nthunit").val(data.TargetDate.NthUnit);
                        var udowold = data.TargetDate.UseDayOfWeek === true ? 1 : 0;
                        if (data.TargetDate.UseEndOfMonth == true) {
                            udowold = 2;
                        }
                        $("#dialog-usedayofweekorlastday").val(udowold);
                        $("#dialog-unittype").val(data.TargetDate.UnitType);
                        $("#dialog-impossibledate").val(data.TargetDate.ImpossibleDayOfMonthBehavior);
                    }
                    $("#dialog-accrualcap").val(data.Cap);
                    $("#dialog-color").val(data.Color);
                    checksentenceboxes();
                    updateEstimate();
                    dialog.dialog("option", "title", "Category " + data.Name);
                    dialog.dialog("open");
                }
            });
        }
        else
        {

        }
    });

//Initialize Category Settings Dialog Box
    var dialog = $( "#dialog-form" ).dialog({
        autoOpen: false,
        height:'auto',
        width:'auto',
        show: {
            effect: "fade",
            duration: 100
        }
    }); // 350, 450

//Initialize Spend Dialog Box
    var dialogSpend = $( "#dialog-form-spend" ).dialog({
        autoOpen: false,
        height:'auto',
        width:'auto',
        show: {
            effect: "fade",
            duration: 100
        }
    });

//Initialize Distribute Dialog Box
    $("#dialog-form-distribute").dialog({
        autoOpen: false,
        height:'auto',
        width:'auto',
        show: {
            effect: "fade",
            duration: 100
        }
    });

//Initialize Transfer Dialog Box
    var dialogSend = $("#dialog-form-send").dialog({
        autoOpen: false,
        height:'auto',
        width:'auto',
        show: {
            effect: "fade",
            duration: 100
        }
    });

//Update Category Submit Button Press
    $("#dialog-submit").click(function(){
        var accrueby = $("#dialog-accrueby").val();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                method: "update_category",
                CategoryId: editingcategoryid,
                AccrueBy: accrueby,
                AccrualAmount: accrueby == 3 ? $("#dialog-auto-target").val() : $("#dialog-accrualamount").val(),
                Cap: $("#dialog-accrualcap").val(),
                Color: $("#dialog-color").val(),
                whentoperform: $("#dialog-whentoperform").val(),
                TargetDate: $("#dialog-auto-date").val(),
                unittype: $("#dialog-unittype").val(),
                nthunit: $("#dialog-nthunit").val(),
                usedayofweekorlastday: $("#dialog-usedayofweekorlastday").val(),
                impossibledate: $("#dialog-impossibledate").val(),
                sendAmount: $("#dialog-send-amount").val(),
                sendCategory: $("#dialog-send-category").val(),
                date: $("#today-date-box").val()
            },
            success: function(){
                dialog.dialog("close");
                refresh();
            }, complete: function(data) {console.log(data);}
        });
    });

//Handle changing $ to % and vice versa in the category edit box
    $("#dialog-accrueby").change(function(){
        var dialogAccrueBy = $("#dialog-accrueby");
        if (["1","2"].indexOf(dialogAccrueBy.val()) > -1)
        {
            $("#dialog-dollars").hide();
            $("#dialog-percent").show();
        }
        else
        {
            $("#dialog-dollars").show();
            $("#dialog-percent").hide();
        }

        if(["0","1","2"].indexOf(dialogAccrueBy.val()) > -1)
        {
            $(".dialog-accby-lt3").show();
            $(".dialog-accby-eq3").hide();
        }
        else
        {
            $(".dialog-accby-lt3").hide();
            $(".dialog-accby-eq3").show();
        }
    });

//Handle Add Funds button press
    $("#addfundsbutton").click(function(){
        var addfundsamount;
        if(addfundsamount = prompt("Enter dollar amount"))
        {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "add_funds",
                    amount: addfundsamount
                },
                success: function(data) {
                    console.log(data);
                    refresh();
                }
            })
        }
    });
//Handle Distribute Funds button press
    $("#cascadebutton").click(function(){
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                method: "cascade",
                date: $("#today-date-box").val()
            },
            /**
             * @var {array} data
             * @prop {number} data.StartingPool
             * @prop {array} data.NewBalanceArray
             * @prop {number} data.NewBalanceArray.i.BeforeBalance
             * @prop {number} data.NewBalanceArray.i.EstimatedAccrualAmount
             * @prop {number} data.NewBalanceArray.i.EffectiveAccrualAmount
             * @prop {number} data.NewBalanceArray.i.AfterBalance
             * @prop {number} data.NewBalanceArray.i.PoolAtPoint
             */
            success: function (data) {
                console.log(data);
                var distribSummary = "<tr><th>Category Name</th>";
                distribSummary += "<th>Balance Before</th>";
                distribSummary += "<th>Accrual Amount</th>";
                distribSummary += "<th>Effective Amount</th>";
                distribSummary += "<th>Balance After</th>";
                distribSummary += "<th>Unallocated</th></tr>";
                distribSummary += "<tr><td></td><td></td><td></td><td></td><td></td><td>$"+numberWithCommas(data.StartingPool)+"</td>";
                for(var i = 0; i < data.NewBalanceArray.length; i++)
                {
                    var displayPool = true;
                    distribSummary += "<tr>";
                    //Name
                    distribSummary += "<td>"+data.NewBalanceArray[i].Name+"</td>";
                    //Balance Before
                    distribSummary += "<td>$"+numberWithCommas(data.NewBalanceArray[i].BeforeBalance)+"</td>";
                    //Accrual Amount
                    var AccrualAmount = "";
                    if(data.NewBalanceArray[i].AccrueBy == 3) {
                        AccrualAmount+="<span class='ui-icon ui-icon-calculator'></span>$" + numberWithCommas(data.NewBalanceArray[i].EstimatedAccrualAmount);// + " with target $" + numberWithCommas(data.NewBalanceArray[i].AccrualAmount) + " by " + new Date(data.NewBalanceArray[i].TargetDate * 1000).format("m/d/Y");
                    }
                    else {
                        if (data.NewBalanceArray[i].AccrueBy == 0) {
                            AccrualAmount += "$";
                        }
                        AccrualAmount += data.NewBalanceArray[i].AccrualAmount;
                        if (data.NewBalanceArray[i].AccrueBy == 1 || data.NewBalanceArray[i].AccrueBy == 2)
                        {
                            AccrualAmount += "%";
                            if (data.NewBalanceArray[i].AccrueBy == 2)
                            {
                                displayPool = (i+1 >= data.NewBalanceArray.length || data.NewBalanceArray[i+1].AccrueBy != 2);
                            }
                        }
                    }
                    distribSummary += "<td>"+AccrualAmount+"</td>";
                    //Effective Amount
                    distribSummary += "<td>$"+numberWithCommas(data.NewBalanceArray[i].EffectiveAccrualAmount)+"</td>";
                    //Balance After
                    distribSummary += "<td>$"+numberWithCommas(data.NewBalanceArray[i].AfterBalance)+"</td>";
                    //Unallocated
                    if (displayPool == true) {
                        distribSummary += "<td>$" + numberWithCommas(data.NewBalanceArray[i].PoolAtPoint) + "</td>";
                    }
                    else
                    {
                        distribSummary += "<td></td>";
                    }
                    distribSummary += "</tr>";
                }
                distribSummary += "<tr><td class='table-exception' colspan='6'><button id='cascade-submit' type='submit'>Submit</td></tr>";
                $("#dialog-form-distribute-table").html(distribSummary);
                $("#cascade-submit").click(function(){
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {
                            method: "cascade_commit",
                            newBalances: data.NewBalanceArray,
                            date: $("#today-date-box").val()
                        },
                        success: function () {
                            refresh();
                            $("#dialog-form-distribute").dialog("close");
                        }
                    });
                });
                $("#dialog-form-distribute").dialog("open");
            }
        });
    });

//Handle Spend Money button press
    $("#spendbutton").click(function(){
        dialogSpend.dialog( "open" );
    });

//Handle Spend Submit button press
    $("#dialog-form-spend-submit").click(function(){
        if (confirm("Are you sure?")){
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    method: "spend",
                    catid: $("#dialog-form-spend-cbo").val(),
                    amount: $("#dialog-form-spend-amount").val()
                },
                /**
                 * @var {array} data
                 * @prop {number} data.TotalBalance
                 */
                success: function (data) {
                    if (data.TotalBalance < 0)
                    {
                        alert("Unable to process expenditure. Your balance would be overdrawn by $" + numberWithCommas(Math.abs(data.TotalBalance)));
                    }
                    else
                    {
                        dialogSpend.dialog("close");
                        refresh();
                    }
                }
            });
        }
    });

//Handle the creation of datepicker boxes
    dialogAutoDate.datepicker({
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        onSelect: updateEstimate
    }).mask("99/99/9999", {completed: function(){
        $(this).data("maskcomplete", true);
        $(this).datepicker("setDate", $("#dialog-auto-date").val());
        updateEstimate();
    }});

    todayDateBox.datepicker({
        showbuttonPanel: true,
        changeMonth: true,
        changeYear: true,
        onSelect: refresh
    }).mask("99/99/9999", {completed: function(){
        $(this).data("maskcomplete", true);
        $(this).datepicker("setDate", $("#today-date-box").val());
        refresh();
    }});
    todayDateBox.datepicker("setDate", new Date());

//Handle change of auto rate parameters
    $("#dialog-auto-target").on("change keyup paste", function(event){
        if (event.which == undefined || (event.which >= 48 && event.which <= 57) || event.which == 190 || event.which == 8 || event.which == 46) {
            setTimeout(updateEstimate, 0);
        }
    });

//Perform the actual update of the estimate field
    function updateEstimate(){
        if (typeof updateEstimate.estimateAjax != "undefined")
        {
            updateEstimate.estimateAjax.abort();
        }
        $("#dialog-accrual-estimate").text("...");
        updateEstimate.estimateAjax = $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                method: "auto_estimate",
                catid: editingcategoryid,
                auto_target: $("#dialog-auto-target").val(),
                auto_date: $("#dialog-auto-date").val(),
                whentoperform: $("#dialog-whentoperform").val(),
                unittype: $("#dialog-unittype").val(),
                nthunit: $("#dialog-nthunit").val(),
                usedayofweekorlastday: $("#dialog-usedayofweekorlastday").val(),
                impossibledate: $("#dialog-impossibledate").val(),
                date: $("#today-date-box").val()
            },
            /**
             * @var {array} data
             * @prop {number} data.estimate
             */
            success: function (data) {
                if (data.estimate > -1) {
                    $("#dialog-accrual-estimate").text(numberWithCommas(data.estimate));
                }
                else {
                    $("#dialog-accrual-estimate").text("0.00");
                }
            }
        });
    }

//Refresh all page data
    function refresh()
    {
        console.log("refreshing");
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                method: "refresh_index"
            },
            /**
             * @var {array} data
             * @prop {array} data.globals
             * @prop {number} data.globals.TotalBalance
             * @prop {number} data.pendingPool
             * @prop {int} data.firstcategory
             * @prop {int} data.nextdate
             * @prop {array} data.categories
             * @prop {int} data.categories.i.LowerPriorityCategory
             */
            success: function(data){
                if (data.success == true) {
                    $("#display-totalbalance").html("$" + numberWithCommas(data.globals.TotalBalance));
                    $("#display-pendingpool").html("$" + numberWithCommas(data.pendingPool));
                    var curcat = data.firstcategory;
                    var newcat = "";
                    var spendcbo = "";
                    var sendcbo = "";
                    var grouppercenttotal = 0;
                    while (curcat != null && typeof curcat != "undefined") {
                        var distance;
                        var backcolor;
                        var altback;
                        if (data.categories[curcat].AccrueBy == 2) {
                            grouppercenttotal += parseFloat(data.categories[curcat].AccrualAmount);
                            distance = 6 + grouppercenttotal*94/100;
                            backcolor = "#DDDDDD";
                            altback = "#F5F5F5";
                        } else {
                            grouppercenttotal = 0;
                            distance = 100;
                            backcolor = "#EEEEEE";
                            altback = "#EEEEEE";
                        }
                        var color = "#000000";
                        if (data.categories[curcat].Cap > 0 && data.categories[curcat].Balance >= data.categories[curcat].Cap) {
                            color = "#009900";
                        }
                        if (data.categories[curcat].Balance < 0) {
                            color = "#FF0000";
                        }
                        backcolor = "linear-gradient(to right," + data.categories[curcat].Color + " 0%," + data.categories[curcat].Color + " 6%," + backcolor + " 6%," + backcolor + " " + distance + "%, "+altback+" " + distance + "%, "+altback+" 100%)";
                        newcat += "<li style='background:" + backcolor + "; color:" + color + "' value='" + data.categories[curcat].CategoryId + "' data-catname='" + data.categories[curcat].Name + "' title='" + data.categories[curcat].Name + "' data-category='" + data.categories[curcat].CategoryId + "' class='category ptrcursor ui-state-default'>";
                        newcat += "<span class='delete-cat ui-icon blackIcon ui-icon-closethick'></span>";
                        newcat += data.categories[curcat].Name;
                        newcat += " ($" + numberWithCommas(data.categories[curcat].Balance) + ")";
                        newcat += "<span style='float:right;position:relative'>";
                        newcat += "<span class='ui-icon cat-send blackIcon ui-icon-arrowreturnthick-1-e'></span>&nbsp;&nbsp;&nbsp;";
                        newcat += "<span class='edit-cat ui-icon blackIcon ui-icon-gear'></span>";
                        newcat += "</span>";
                        newcat += "</li>";
                        newcat += "<span id='catdetails-" + data.categories[curcat].CategoryId + "' class='catdetails hidden'>";
                        newcat += "<table class='mid-align'>";
                        newcat += "<tr><td>Name:</td><td>" + data.categories[curcat].Name + "</td></tr>";
                        newcat += "<tr><td>Balance:</td><td>$" + numberWithCommas(data.categories[curcat].Balance) + "</td></tr>";
                        newcat += "<tr><td>AccrueBy:</td><td>" + accrueByArray[data.categories[curcat].AccrueBy] + "</td></tr>";
                        newcat += "<tr><td>" + (data.categories[curcat].AccrueBy == 3 ? "Target Amount" : "Accrual Rate") + ":</td><td>" + (data.categories[curcat].AccrueBy == 0 || data.categories[curcat].AccrueBy == 3 ? "$" : "") + numberWithCommas(data.categories[curcat].AccrualAmount) + (data.categories[curcat].AccrueBy == 1 || data.categories[curcat].AccrueBy == 2 ? "%" : "") + "</td></tr>";
                        if (data.categories[curcat].AccrueBy == 3) {
                            newcat += "<tr id='load-nextdate-cat-" + curcat + "'><td>Target Date:</td><td>Still calculating, try again later...</tr>";
                            newcat += "<tr id='load-estimate-cat-" + curcat + "'><td>Calculated Accrual Rate:</td><td>Still calculating, try again later...</td></tr>";
                            $.ajax({
                                type: "POST",
                                url: "ajax.php",
                                data: {
                                    method: "current_auto_estimate",
                                    catid: curcat,
                                    date: $("#today-date-box").val()
                                },
                                success: function (data) {
                                    $("#load-estimate-cat-" + data.catid).html("<td>Calculated Accrual Rate:</td><td>$" + numberWithCommas(data.estimate) + "</td>");
                                    $("#load-nextdate-cat-" + data.catid).html("<td>Target Date:</td><td>" + new Date(data.nextdate * 1000).format("m/d/Y") + "</td>");
                                }
                            });
                        }
                        newcat += "<tr><td>Max Accrual Amount:</td><td>$" + numberWithCommas(data.categories[curcat].Cap) + "</td></tr>";
                        newcat += "</table>";
                        newcat += "</span>";
                        spendcbo += "<option value='" + data.categories[curcat].CategoryId + "'>" + data.categories[curcat].Name + " ($" + numberWithCommas(data.categories[curcat].Balance) + ")</option>";
                        sendcbo += "<option value='" + curcat + "'>" + data.categories[curcat].Name + "</option>";
                        curcat = data.categories[curcat].LowerPriorityCategory;
                    }
                    $("#dialog-send-category").html("<option value='NULL'>Unallocated</option>" + sendcbo);
                    newcat += "<li style='background-color:#F5F5F5; color:#999999' value='NULL' data-catname='Unallocated' data-category='NULL' class='category stdcursor fixed ui-state-default'>";
                    newcat += "Unallocated";
                    newcat += " ($" + numberWithCommas(data.pendingPool) + ")";
                    newcat += "<span style='float:right;position:relative'>";
                    newcat += "<span class='ui-icon cat-send blackIcon ui-icon-arrowreturnthick-1-e'></span>&nbsp;&nbsp;&nbsp;";
                    newcat += "</span>";
                    newcat += "</li>";
                    $("#sortable").html(newcat).sortable("option", "cancel", ".fixed");
                    $("#dialog-form-spend-cbo").html(spendcbo);

                    //Define Category hover tooltips
                    $(".category").tooltip({
                        items: "[category], [title]",
                        content: function () {
                            var element = $(this);
                            if (element.is("[category]")) {
                                return $("#catdetails-" + element.attr("data-category")).html();
                            }
                            if (element.is("[title]")) {
                                return element.attr("title");
                            }
                        },
                        position: {
                            my: "left top",
                            at: "right+10 top"
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {
                            method: "cascade",
                            UseProjectedPaycheck: true,
                            date: todayDateBox.val()
                        },
                        success: function(data) {
                            console.log(data);
                            $("#display-predicted-unallocated").text("$"+numberWithCommas(data.NewBalanceArray[data.NewBalanceArray.length-1].PoolAtPoint));
                        },
                        error: function(data, status, error){
                            console.log(data);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
                else
                {
                    console.log("Set up profile first");
                }
            }
        });
    }

    refresh();

//Format a number with commas
    function numberWithCommas(x) {
        x = parseFloat(x);
        x = x.toFixed(2);
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});