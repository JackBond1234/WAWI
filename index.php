<?php
include("includes.php");
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/js/jquery.js"></script>
    <script src="js/index.js"></script>
    <script src="/js/angular.js"></script>
    <script src="js/angular-animate.js"></script>
    <script src="js/dndLists.js"></script>
    <link rel="stylesheet" href="css/index.css">
<!--    <link rel="stylesheet" href="../js/jqui/jquery-ui.css">-->
    <link rel="stylesheet" type="text/css" href="css/grid12.css">

</head>
<body ng-app="index">
<div id="MainContainer" ng-controller="rootController">
    <div ng-class="{'col-md-4': !Mobile}" class="MainColumn" id="ApplicationContainer" ng-controller="applicationController" ng-include="'indexApplication.html'">
    </div>
    <div ng-class="{'col-md-4': !Mobile}" class="MainColumn" id="CategoryContainer" ng-controller="categoryController" ng-include="'indexCategories.html'">
    </div>
    <div ng-class="{'col-md-4': !Mobile}" ng-if="root.SelectedCategory != -1" class="MainColumn" id="DetailContainer" ng-controller="detailController" ng-include="'indexDetails.html'">
    </div>
</div>
</body>
</html>
