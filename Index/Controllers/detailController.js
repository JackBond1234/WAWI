 angular.module('index').controller('detailController', function($scope, $log, $window){
    $scope.$watch("root.SelectedCategory", function(SelectedCategory) {
        $.ajax({
            method: "POST",
            url: "../../../ajax.php",
            data: {
                method: "get_category",
                catid: SelectedCategory
            },
            success: function (data) {
                console.log(data);
                if (data.Success) {
                    $scope.$apply(function () {
                        $scope.Details = [];
                        $.each(data, function(elem){
                            $scope.Details.push(elem + " : " + data[elem]);
                        });
                    });
                }
            }
        });
    });
});