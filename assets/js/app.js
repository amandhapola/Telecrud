angular.module('telecrud',['ngRoute'])
.config(['$routeProvider',function($routeProvider){
	$routeProvider
					.when('/',{templateUrl:'assets/template/list.html',controller:listController})
					.when('/add-user',{templateUrl:'assets/template/add-new.html',controller:addController})
          .when('/edit/:id',{templateUrl:'assets/template/edit.html',controller:editController})
					.otherwise({redirectTo:'/'});
}]);

function listController($scope,$http){
  console.log("inside list controller");
	$http.get('/api/users').success(function(data){
		console.log("inside success");
    console.log(data);
    $scope.users=data;
	})
  .error(function(data) {
        console.log("internal server error");
});
}

function addController($scope, $http, $location) {
  $scope.master = {};
  $scope.activePath = null;

  $scope.add_new = function(user, AddNewForm) {
    	console.log(user);
      // console.log(JSON.stringify(user));
   	    $http.post('api/add_user',user).success(function(){
          console.log("inside addd success");
      	$scope.reset();
      	$scope.activePath = $location.path('/');
    	});

    	$scope.reset = function() {
      	$scope.user = angular.copy($scope.master);
    	};
    	$scope.reset();
 };
}

function editController($scope,$http,$location,$routeParams)
{
    var id=$routeParams.id;
    $scope.activePath=null;
    //fetch the user according to id
    $http.get('/api/users/'+id).success(function(data){
      console.log('right now inside edit controller and the data is '+data);
      $scope.users=data;
    });
    $scope.update=function(user){
      $http.put('/api/users/'+id,user).success(function(data){
        $scope.users=data; //to update the list again
        $scope.activePath=$location.path('/');
      });
    };

    $scope.delete=function(user){
      console.log('inside delete');
     console.log(user);
     $http.delete('/api/users'+user.id);
     // $scope.users=data;
     $scope.activePath=$location.path('/'); 
    };


}