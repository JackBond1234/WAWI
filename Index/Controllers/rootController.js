angular.module('index').controller('rootController', function($scope, $log, $window){
    $scope.Mobile = $window.innerWidth < 599;
    $(window).resize(function(){
        $scope.$apply(function(){
            $scope.Mobile = $window.innerWidth < 599;
            if (!$scope.Mobile && $scope.root.ApplicationView == "Categories") {$scope.root.ApplicationView = "Reports";}
        });
    });

    $scope.root = {ApplicationView: $window.innerWidth < 599 ? "Categories" : "Reports",
                   ShowAppMenu: false,
                   ShowDetailMenu: false,
                   ShowUserMenu: false,
                   SelectedCategory: -1};
});