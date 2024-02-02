var app = angular.module("myShoppingList", []);
app.controller("myCtrl", function($scope) {
  $scope.produtos = ["Pão", "Queijo", "Suco"];
  $scope.addItem = function(){
    if($scope.itemNovo != null){
      if($scope.produtos.indexOf($scope.itemNovo) == -1){
          $scope.produtos.push($scope.itemNovo);
      } else {
          $scope.errortext = "O item \""+$scope.itemNovo+"\" já está no carrinho!";
          alert($scope.errortext);
      }
    }
    
  }
  $scope.removeItem = function(x){
    $scope.produtos.splice(x,1);
  }
});

/* Desativando o Refresh do botão submit */
document.querySelector('#addItem').addEventListener('click', function(e){
    e.preventDefault();
})