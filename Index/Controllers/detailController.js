angular.module('index').controller('detailController', function($scope, $log, $window, $timeout){
    $scope.changeBrightness = function(color, percent) {
        if (color == undefined){ color = "#FFFFFF"; }
        if (percent == undefined) { percent = 0; }
        var f=parseInt(color.slice(1),16),t=percent<0?0:255,p=percent<0?percent*-1:percent,R=f>>16,G=f>>8&0x00FF,B=f&0x0000FF;
        return "#"+(0x1000000+(Math.round((t-R)*p)+R)*0x10000+(Math.round((t-G)*p)+G)*0x100+(Math.round((t-B)*p)+B)).toString(16).slice(1);
    };

    $scope.calculateDetailTop = function(){
        //FIX THE BOTTOM PART OF THIS
        var DB = $("#DetailBubble");
        var EL = $(".categories-list-item.ActiveCategory");

        if (EL.length > 0) {
            var body = document.body,
                html = document.documentElement;
            var docheight = Math.max(body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight);

            //console.log("DB TOP: " + DB.css("top"));
            //console.log("EL TOP: " + EL.css("top"));
            //console.log("docheight: " + docheight);

            var proposedTop = EL.offset().top + EL.outerHeight() / 2 - DB.outerHeight() / 2;

            if (proposedTop + DB.outerHeight() > docheight) {
                proposedTop = docheight - DB.outerHeight();
            }
            if (proposedTop < 0) {
                proposedTop = 0;
            }
        } else if (DB.length > 0) {
            proposedTop = DB.css("top");
        } else {
            proposedTop = 0;
        }
        return proposedTop;
    };

    $scope.XButtonClicked = function(){
        $scope.root.SelectedCategory = -1;
        $("body").removeClass("noScroll");
    };

    $scope.$watch("root.SelectedCategory", function(SelectedCategory) {
        if ($scope.Mobile) {
            $("body").addClass("noScroll");
        }
        $scope.Details = {Name: "Loading..."};
        $timeout(function(){
            var DB = $("#DetailBubble");
            if (DB.length > 0){
                DB.css({"top":$scope.calculateDetailTop()+"px"});
            }
        });
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
                        console.log(data);
                        $scope.Details = data;
                    });
                }
            }
        });
    });
});