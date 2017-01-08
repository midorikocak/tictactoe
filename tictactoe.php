<?php
include('vendor/autoload.php');

$tictactoe = new MidoriKocak\Tictactoe(3);

if(isset($_POST['board'])){

    $board = json_decode($_POST['board']);
    $nextMove = $tictactoe->makeMove($board ,'O');

    if(!empty($nextMove)){
        $board[$nextMove[1]][$nextMove[0]] = $nextMove[2];
    }
    else{

    }
    $response = ['message'=> $tictactoe->message, 'board'=>$board];
    echo json_encode($response);
}
?>