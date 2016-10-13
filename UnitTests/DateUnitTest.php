<?php

include("../includes.php");

$UnitTypes = array("day", "week", "month", "year");

$DateArray = array();

//By Day, same day
$DateModel = new DateModel();
$DateModel->TestName = "By Day, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Day, different matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Day, different matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-05");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//Day prior to start date
$DateModel = new DateModel();
$DateModel->TestName = "Day prior to start date";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2015-01-05");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//Impossible day
$DateModel = new DateModel();
$DateModel->TestName = "Impossible day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-90-35");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Day, zero units same day
$DateModel = new DateModel();
$DateModel->TestName = "By Day, zero units same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Day, zero units different day
$DateModel = new DateModel();
$DateModel->TestName = "By Day, zero units different day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-02");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Day, crossing DST Spring
$DateModel = new DateModel();
$DateModel->TestName = "By Day, crossing DST Spring";
$DateModel->Date = strtotime("2016-03-13");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-14");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Day, crossing DST Fall
$DateModel = new DateModel();
$DateModel->TestName = "By Day, crossing DST Fall";
$DateModel->Date = strtotime("2016-11-06");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-11-07");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 5th Day, same day
$DateModel = new DateModel();
$DateModel->TestName = "By 5th Day, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 5th Day, different matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 5th Day, different matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-16");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 5th Day, non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 5th Day, non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-10");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 5th Day, cross over year
$DateModel = new DateModel();
$DateModel->TestName = "By 5th Day, cross over year";
$DateModel->Date = strtotime("2015-12-29");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-03");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Week, same day
$DateModel = new DateModel();
$DateModel->TestName = "By Week, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Week, different month-crossing matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Week, different month-crossing matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-05");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Week, non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Week, different month-crossing matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-01");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Week, zero units same day
$DateModel = new DateModel();
$DateModel->TestName = "By Week, zero units same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Week, zero units different day
$DateModel = new DateModel();
$DateModel->TestName = "By Week, zero units same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-02");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Week, crossing DST Spring
$DateModel = new DateModel();
$DateModel->TestName = "By Week, crossing DST Spring";
$DateModel->Date = strtotime("2016-03-10");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-17");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Week, crossing DST Fall
$DateModel = new DateModel();
$DateModel->TestName = "By Week, crossing DST Fall";
$DateModel->Date = strtotime("2016-11-05");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-11-12");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 5th Week, different matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 5th Week, different matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 5;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-05");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, same day
$DateModel = new DateModel();
$DateModel->TestName = "By Month, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, different non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Month, different non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-02");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month, different matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Month, different matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, impossible day end of month
$DateModel = new DateModel();
$DateModel->TestName = "By Month, impossible day end of month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, impossible day skip
$DateModel = new DateModel();
$DateModel->TestName = "By Month, impossible day skip";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month, zero units same day
$DateModel = new DateModel();
$DateModel->TestName = "By Month, zero units same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, zero units different day
$DateModel = new DateModel();
$DateModel->TestName = "By Month, zero units different day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-01");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months, different matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months, different matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Months, different non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months, different non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-01");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 3 Months, impossible day end of month
$DateModel = new DateModel();
$DateModel->TestName = "By 3 Months, impossible day end of month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 3;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-04-30");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 3 Months, impossible day skip
$DateModel = new DateModel();
$DateModel->TestName = "By 3 Months, impossible day skip";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 3;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-04-30");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month, end of month override
$DateModel = new DateModel();
$DateModel->TestName = "By Month, end of month override";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-04-30");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month, end of month override, impossible end of month, and same month
$DateModel = new DateModel();
$DateModel->TestName = "By Month, end of month override, impossible end of month, and same month";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-31");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, same day
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, different non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-02");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month Day of week, different non-matching day different month
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different non-matching day different month";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-02");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 1st on the 1st matching not the 1st
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 1st on the 1st matching not the 1st";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-05");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 1st on the 1st same weekday different nth
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 1st on the 1st same weekday different nth";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-12");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 1st on the 7th matching
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 1st on the 7th matching";
$DateModel->Date = strtotime("2016-01-07");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-04");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 4th on the 28th matching
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 4th on the 28th matching";
$DateModel->Date = strtotime("2016-01-28");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-25");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 5th on the 31st, end of month
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 5th on the 31st, end of month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, different matching day 5th on the 31st, skip
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, different matching day 5th on the 31st, skip";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 13 Months Day of week, 12 months minus one day check
$DateModel = new DateModel();
$DateModel->TestName = "By 13 Months Day of week, 12 months minus one day check";
$DateModel->Date = strtotime("2016-03-13");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1234;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-03-12");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 1st matching not the 1st wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 1st on the 1st wrong month";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-05");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 1st same weekday different nth wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months  Day of week, different matching day 1st on the 1st same weekday different nth wrong month";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-12");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 7th matching wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 1st on the 7th matching wrong month";
$DateModel->Date = strtotime("2016-01-07");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-04");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 4th on the 28th matching wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 4th on the 28th matching wrong month";
$DateModel->Date = strtotime("2016-01-28");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-25");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 5th on the 31st, end of month wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 5th on the 31st, end of month wrong month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 5th on the 31st, skip wrong month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 5th on the 31st, skip wrong month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-29");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 1st matching not the 1st
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 1st on the 1st";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-04");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 1st same weekday different nth
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months  Day of week, different matching day 1st on the 1st same weekday different nth";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-11");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 1st on the 7th matching
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 1st on the 7th matching";
$DateModel->Date = strtotime("2016-01-07");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-03");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 4th on the 28th matching
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 4th on the 28th matching";
$DateModel->Date = strtotime("2016-01-28");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-24");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 5th on the 31st, end of month
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 5th on the 31st, end of month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-31");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Months Day of week, different matching day 5th on the 31st, skip
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Months Day of week, different matching day 5th on the 31st, skip";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-31");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month Day of week, zero units same day
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, zero units same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, zero units different day
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, zero units different day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 0;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-05");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Month Day of week, end of month override
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, end of month override";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-04-30");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Month Day of week, end of month override, impossible end of month, and same month
$DateModel = new DateModel();
$DateModel->TestName = "By Month Day of week, end of month override, impossible end of month, and same month";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 1; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-31");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Year, same day
$DateModel = new DateModel();
$DateModel->TestName = "By Year, same day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Year, matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Year, matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Year, non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By Year, non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-05-15");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By 2 Years, matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Years, matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2018-01-01");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By 2 Years, non-matching day
$DateModel = new DateModel();
$DateModel->TestName = "By 2 Years, non-matching day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-01-01");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//By Year, impossible day end of month
$DateModel = new DateModel();
$DateModel->TestName = "By Year, impossible day end of month";
$DateModel->Date = strtotime("2016-02-29");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-02-28");
$DateModel->ExpectedResult = true;
$DateArray[] = $DateModel;

//By Year, impossible day skip
$DateModel = new DateModel();
$DateModel->TestName = "By Year, impossible day skip";
$DateModel->Date = strtotime("2016-02-29");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2017-02-28");
$DateModel->ExpectedResult = false;
$DateArray[] = $DateModel;

//============================================================================================Occurrences Tests

//Occurrences, By Day, one week
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Day, one week";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-07");
$DateModel->ExpectedResult = 7;
$OccDateArray[] = $DateModel;

//Occurrences, By 2 Days, one week
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By 2 Days, one week";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 0; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-07");
$DateModel->ExpectedResult = 4;
$OccDateArray[] = $DateModel;

//Occurrences, By Week, one week
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Week, one week";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-07");
$DateModel->ExpectedResult = 1;
$OccDateArray[] = $DateModel;

//Occurrences, By Week, one week and a day
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Week, one week and a day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-08");
$DateModel->ExpectedResult = 2;
$OccDateArray[] = $DateModel;

//Occurrences, By Week, one week and a day
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Week, one week and a day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 1; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 2;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-01-21");
$DateModel->ExpectedResult = 2;
$OccDateArray[] = $DateModel;

//Occurrences, By Month, one month and one day
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Month, one month and one day";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-02-01");
$DateModel->ExpectedResult = 2;
$OccDateArray[] = $DateModel;

//Occurrences, By Month, three months, one day one impossible date, end of month
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Month, three months, one day one impossible date, end of month";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 0; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-31");
$DateModel->ExpectedResult = 3;
$OccDateArray[] = $DateModel;

//Occurrences, By Month, three months, one day one impossible date, skip
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Month, three months, one day one impossible date, skip";
$DateModel->Date = strtotime("2016-01-31");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-31");
$DateModel->ExpectedResult = 2;
$OccDateArray[] = $DateModel;

//Occurrences, By Month, three months, one day, end of month override
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By Month, three months, one day, end of month override";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 2; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2016-03-31");
$DateModel->ExpectedResult = 3;
$OccDateArray[] = $DateModel;

//Occurrences, By 5 years
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By 5 years";
$DateModel->Date = strtotime("2016-01-01");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 0; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2020-12-31");
$DateModel->ExpectedResult = 5;
$OccDateArray[] = $DateModel;

//Occurrences, By 4 years, one day, 3 impossible dates
$DateModel = new DateModel();
$DateModel->TestName = "Occurrences, By 4 years, one day, 3 impossible dates";
$DateModel->Date = strtotime("2016-02-29");
$DateModel->UnitType = 3; // 0=day, 1=week, 2=month, 3=year
$DateModel->NthUnit = 1;
$DateModel->ImpossibleDayOfMonthBehavior = 1; // 0=endofmonth, 1=skip
$DateModel->UseEndOfMonth = 1; // 0=dont, 1=use
$DateModel->UseDayOfWeek = 0; // 0=31st of feb, 1=4th Sun of feb
$DateModel->TargetDate = strtotime("2020-02-29");
$DateModel->ExpectedResult = 2;
$OccDateArray[] = $DateModel;


/**
 * @var int $index
 * @var DateModel $DateModel
 */
foreach($DateArray as $index => $DateModel) {
    if ($DateModel->ExpectedResult == DateBusinessObject::IsDateOnThisDay($DateModel, $DateModel->TargetDate))
    {
        echo $index+1 . ": Success";
    }
    else
    {
        echo $index+1 . ": Failure - " . $DateModel->TestName;
    }
    echo "<br>";
}

echo "-----------Occurrences tests<br>";

/**
 * @var int $index
 * @var DateModel $DateModel
 */
foreach($OccDateArray as $index => $DateModel) {
    if ($DateModel->ExpectedResult == DateBusinessObject::OccurrencesBetweenDates($DateModel, $DateModel->Date, $DateModel->TargetDate))
    {
        echo $index+1 . ": Success";
    }
    else
    {
        echo $index+1 . ": Failure - " . $DateModel->TestName;
    }
    echo "<br>";
}