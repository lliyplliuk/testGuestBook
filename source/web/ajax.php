<?php
require __DIR__ . '/../vendor/autoload.php';

use types\{
    Board,
    Msg
};

$board = new Board();
$request = json_decode(file_get_contents('php://input'), true);
$msg = new Msg();
$id = (int)($request['id'] ?? 0);
$msg->nameAuthor = (string)($request['name'] ?? '');
$msg->text = (string)($request['text'] ?? '');
switch ($_GET['action'] ?? 0) {
    case 'get':
        $board->checkChange();
        echo $board->asJson();
        break;
    case 'change':
        $board->changeMsg($msg, $id);
        $board->save();
        echo json_encode(['data' => true]);
        break;
    case 'add':
        if (empty($id))
            echo json_encode(['data' => $board->addMsg($msg)]);
        else
            echo json_encode(['data' => $board->addAnswer($msg, $id)]);
        $board->save();
        break;

}