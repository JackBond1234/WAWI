<?
include("includes.php");
?>

<html>
<head>
    <link rel="stylesheet" href="js/jqui/jquery-ui.css">
    <script src="js/jquery.js"></script>
    <script src="js/jqui/jquery-ui.js"></script>
    <script src="js/jquery-masked-input.min.js"></script>
    <script src="js/date.format.min.js"></script>
    <script src="js/angular.js"></script>
    <script src="js/indexOld.js"></script>

    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 500px; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
        #sortable li span { position: absolute; margin-left: -1.3em; }
        .hidden {display: none;}
        #dialog-form td {padding: 5px;}
        .stdcursor {
            cursor: default;
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none;   /* Chrome/Safari/Opera */
            -khtml-user-select: none;    /* Konqueror */
            -moz-user-select: none;      /* Firefox */
            -ms-user-select: none;       /* IE/Edge */
            user-select: none;           /* non-prefixed version, currently*/
        }
        .ptrcursor {
            cursor: all-scroll;
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none;   /* Chrome/Safari/Opera */
            -khtml-user-select: none;    /* Konqueror */
            -moz-user-select: none;      /* Firefox */
            -ms-user-select: none;       /* IE/Edge */
            user-select: none;           /* non-prefixed version, currently*/
        }
        .edit-cat, .delete-cat, .cat-send{
            cursor: pointer;
            color:#000000;
        }
        .ui-icon.blackIcon { background-image: url("/js/jqui/images/ui-icons_000000_256x240.png"); }
        .mid-align tr td:first-child{text-align:center;}

        #dialog-form-distribute-table tr:not(:first-child):not(:last-child) {
            background-color: #F5F5F5;
        }
        #dialog-form-distribute-table tr:last-child {
            text-align:center;
        }
        #dialog-form-distribute-table td {
            padding: 5px;
        }
        #dialog-form-distribute-table th {
            border-bottom: 1px solid;
            background-color: #FFFFFF;
            padding: 5px;
        }
        #dialog-form-distribute-table {
            border-collapse: separate;
            border-spacing: 4px 0px;
        }
        .ui-icon {
            display: inline-block;
        }
    </style>
    <script>

    </script>
</head>
<body>
Total:&nbsp;<span id="display-totalbalance">Loading...</span>&nbsp;|&nbsp;
Predicted Unallocated:&nbsp;<span id="display-predicted-unallocated">Loading...</span>
&nbsp;
&nbsp;<a href="profile.php">Edit Profile</a>
&nbsp;<a href="rebalance.php">Rebalance Rules</a>
<br>
<button id='addcategorybutton'>Add Category</button>
<button id='addfundsbutton'>Add Funds</button>
<button id='cascadebutton'>Distribute Funds</button>
<button id='spendbutton'>Spend Money</button>
&nbsp;Today's Date:&nbsp;<input id='today-date-box' class="datepicker" type='text'>
<br><br>
<ul id="sortable"></ul>


<div id="dialog-form" title="Category">
    <table>
        <tr>
            <td>
                <label>Balance:</label>
            </td>
            <td>
                <span id="dialog-balance">$x.xx</span>
            </td>
        </tr>
        <tr>
            <td>
                <label>Accrue&nbsp;By:</label>
            </td>
            <td>
                <select id="dialog-accrueby">
                    <option value='0'>Flat Amount</option>
                    <option value='1'>Percentage Individual</option>
                    <option value='2'>Percentage Group</option>
                    <option value='3'>Automatic</option>
                </select>
            </td>
        </tr>
        <tr class="dialog-accby-eq3">
            <td>
                <label>Target&nbsp;Amount:</label>
            </td>
            <td>
                $<input id="dialog-auto-target" type="text">
            </td>
        </tr>
        <tr class="dialog-accby-eq3">
            <td>
                <label>Target&nbsp;Date:</label>
            </td>
            <td>
                <select id="dialog-whentoperform">
                    <option value="0">On a specific date</option>
                    <option value="1">Periodically</option>
                </select><span id="simple">.</span>
                <span class="periodically">
                starting on
                </span>
                <span id="dialog-date">
                    <input id='dialog-auto-date' class="datepicker" type='text'>
                </span>
                <span class="periodically">
                <br>and on every <input id="dialog-nthunit" type="text"/><span id="stndrdth">th</span>
                    <select id="dialog-unittype">
                        <option value="0">day</option>
                        <option value="1">week</option>
                        <option value="2">month</option>
                        <option value="3">year</option>
                    </select>
                afterward<span class="monthly"><br>
                    <select id="dialog-usedayofweekorlastday">
                        <option value="0">on the same day of the month as the start</option>
                        <option value="1">on the same week and day of week as the start</option>
                        <option value="2">on the last day of the month</option>
                    </select></span>.
                <span id="impossibledate" class="monthly">
                <br>If the day does not exist,
                        <select id="dialog-impossibledate">
                            <option value="0">perform the operation at the end of the month instead</option>
                            <option value="1">do not perform the operation</option>
                        </select>.
                    </span>
                </span>

            </td>
        </tr>
        <tr>
            <td>
                <label>Accrual&nbsp;Rate:</label>
            </td>
            <td class="dialog-accby-lt3">
                <span id='dialog-dollars'>$</span><input id='dialog-accrualamount' type='text'><span id='dialog-percent'>%</span>
            </td>
            <td class="dialog-accby-eq3">
                $<span id="dialog-accrual-estimate">0.00</span>
            </td>
        </tr>
        <tr>
            <td>
                <label>Max&nbsp;Accrual&nbsp;Amount:</label>
            </td>
            <td>
                $<input id='dialog-accrualcap' type='text'>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tag&nbsp;Color:</label>
            </td>
            <td>
                <input id='dialog-color' type='text'>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button id='dialog-submit'>Submit</button>
            </td>
        </tr>
    </table>
</div>

<div id="dialog-form-spend" title="Spend Money">
    <table>
        <tr>
            <td>
                <label>Category: </label>
            </td>
            <td>
                <select id="dialog-form-spend-cbo">
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label>Amount: </label>
            </td>
            <td>
                $<input type="text" id="dialog-form-spend-amount"/>
            </td>
        </tr>
        <tr>
            <td>
                <button id='dialog-form-spend-submit' type="submit">Submit</button>
            </td>
        </tr>
    </table>
</div>

<div id="dialog-form-distribute" title="Distribute Funds">
    <table id="dialog-form-distribute-table">

    </table>
</div>

<div id="dialog-form-send" title="Transfer">
    <table>
        <tr>
            <td>
                <label>Send:</label>
            </td>
            <td>
                $<input id='dialog-send-amount' type='text'> to
                <select id='dialog-send-category'>
                    <option>Loading...</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <button id='dialog-form-send-submit' type="submit">Submit</button>
            </td>
        </tr>
    </table>
</div>

<div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>

</body>
</html>