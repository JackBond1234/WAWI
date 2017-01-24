<?php
include_once("includes.php");

if (!empty($_POST)) {
  $GlobalsDataAccess = new GlobalsDataAccess();
  $GlobalsModel = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
  if ($GlobalsModel == null) {
    $GlobalsModel = new GlobalsModel();
    $GlobalsModel->Date = new DateModel();
  }

  if (isset($_POST["newtotalbalance"]) && is_numeric($_POST["newtotalbalance"])) {
    $GlobalsModel->TotalBalance = $_POST["newtotalbalance"];
  }
  if (isset($_POST["newexpectedincome"]) && is_numeric($_POST["newexpectedincome"])) {
    $GlobalsModel->ExpectedPeriodIncome = $_POST["newexpectedincome"];
  }
  if (isset($_POST["date"]) && strtotime($_POST["date"]) !== false
      && isset($_POST["nthunit"]) && is_numeric($_POST["nthunit"])
      && isset($_POST["unittype"]) && is_numeric($_POST["unittype"]))
  {
    $GlobalsModel->Date->Date = strtotime($_POST["date"]);
    $GlobalsModel->Date->NthUnit = $_POST["nthunit"];
    $GlobalsModel->Date->UnitType = $_POST["unittype"];
    $GlobalsModel->Date->UseDayOfWeek = (isset($_POST["usedayofweek"]) && is_numeric($_POST["usedayofweek"]) ? $_POST["usedayofweek"] : 0);
    if ($GlobalsModel->Date->UseDayOfWeek == 2)
    {
      $GlobalsModel->Date->UseDayOfWeek = 0;
      $GlobalsModel->Date->UseEndOfMonth = 1;
    }
    else
    {
      $GlobalsModel->Date->UseEndOfMonth = 0;
    }
    $GlobalsModel->Date->ImpossibleDayOfMonthBehavior = (isset($_POST["impossibledate"]) && is_numeric($_POST["impossibledate"]) ? $_POST["impossibledate"] : 0);
  }
  GlobalsBusinessObject::InsertOrUpdate($GlobalsModel);

  header( 'Location: '.$_SERVER['PHP_SELF']."?success=true" );
  die();
}
else
{
  $GlobalsDataAccess = new GlobalsDataAccess();
  $GlobalsModel = GlobalsBusinessObject::FirstResultToModel($GlobalsDataAccess->Select());
  if ($GlobalsModel == null)
  {
    $GlobalsModel = new GlobalsModel();
    $GlobalsModel->Date = new DateModel();
  }
?>

<html>
<head>
  <script src="js/jquery.js"></script>
  <script>
    $(document).ready(function() {
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

      whentoperform.change(checksentenceboxes);
      nthunit.keyup(checksentenceboxes);
      usedayofweekorlastday.change(checksentenceboxes);
      unittype.change(checksentenceboxes);

      function checksentenceboxes() {
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

      $("#success-message").fadeOut(3000);
    });
  </script>
</head>
<body>
  <a href="Index/Views/Main/index.php">Home</a>
  <form method='POST'>
    <div class='card padding' style='background-color:#efefef; padding:5px; margin:2px;' >
      <h4 class='card-title'>Total Balance</h4>
      <p class='card-text'><span>$<input type='text' name='newtotalbalance' value='<?php echo (isset($GlobalsModel->TotalBalance)?number_format($GlobalsModel->TotalBalance, 2, ".", ""):"0.00"); ?>'></span></p>
    </div>
    <div class='card' style='background-color:#efefef; padding:5px; margin:2px;'>
      <h4 class='card-title'>Expected income per paycheck</h4>
      <p class='card-text'><span>$<input type='text' name='newexpectedincome' value='<?php echo (isset($GlobalsModel->ExpectedPeriodIncome)?number_format($GlobalsModel->ExpectedPeriodIncome, 2, ".", ""):"0.00"); ?>'></span></p>
    </div>
    <div class='card' style='background-color:#efefef; padding:5px; margin:2px;'>
      <h4 class='card-title'>Time between paychecks</h4>
      <span class="periodically">
      Starting on
      </span>
      <span id="dialog-date">
          <input id="dialog-datebox" name="date" type="text" value="<?php echo (isset($GlobalsModel->Date->Date)?date("Y-m-d", $GlobalsModel->Date->Date):""); ?>"/>
      </span>
      <span class="periodically">
      and on every <input id="dialog-nthunit" name="nthunit" type="text" value="<?php echo (isset($GlobalsModel->Date->NthUnit)?$GlobalsModel->Date->NthUnit:"")?>"/><span id="stndrdth">th</span>
          <select id="dialog-unittype" name="unittype">
            <option <?php echo (isset($GlobalsModel->Date->UnitType)&&$GlobalsModel->Date->UnitType==0?"selected":""); ?> value="0">day</option>
            <option <?php echo (isset($GlobalsModel->Date->UnitType)&&$GlobalsModel->Date->UnitType==1?"selected":""); ?> value="1">week</option>
            <option <?php echo (isset($GlobalsModel->Date->UnitType)&&$GlobalsModel->Date->UnitType==2?"selected":""); ?> value="2">month</option>
            <option <?php echo (isset($GlobalsModel->Date->UnitType)&&$GlobalsModel->Date->UnitType==3?"selected":""); ?> value="3">year</option>
          </select>
      afterward<span class="monthly">
          <select id="dialog-usedayofweekorlastday" name="usedayofweek">
            <option <?php echo (isset($GlobalsModel->Date->UseDayOfWeek)&&$GlobalsModel->Date->UseDayOfWeek==0&&$GlobalsModel->Date->UseEndOfMonth==0?"selected":""); ?> value="0">on the same day of the month as the start</option>
            <option <?php echo (isset($GlobalsModel->Date->UseDayOfWeek)&&$GlobalsModel->Date->UseDayOfWeek==1&&$GlobalsModel->Date->UseEndOfMonth==0?"selected":""); ?> value="1">on the same week and day of week as the start</option>
            <option <?php echo (isset($GlobalsModel->Date->UseEndOfMonth)&&$GlobalsModel->Date->UseEndOfMonth==1?"selected":""); ?> value="2">on the last day of the month</option>
          </select></span>.
      <span id="impossibledate" class="monthly">
      If the day does not exist,
              <select id="dialog-impossibledate" name="impossibledate">
                <option <?php echo (isset($GlobalsModel->Date->ImpossibleDayOfMonthBehavior)&&$GlobalsModel->Date->ImpossibleDayOfMonthBehavior==0?"selected":""); ?> value="0">perform the operation at the end of the month instead</option>
                <option <?php echo (isset($GlobalsModel->Date->ImpossibleDayOfMonthBehavior)&&$GlobalsModel->Date->ImpossibleDayOfMonthBehavior==1?"selected":""); ?> value="1">do not perform the operation</option>
              </select>.
          </span>
      </span>
    </div>
  <div><input class='btn btn-primary' type='submit'></div><?php if (isset($_GET["success"]) && $_GET["success"] == true) {echo "&nbsp;&nbsp;<span id='success-message'>Saved</span>";} ?>
  </form>
</body>
</html>
<?php } ?>
