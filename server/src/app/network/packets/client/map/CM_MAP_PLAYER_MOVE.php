<?php

namespace App\Network\Packets\Client\Map;
use LogicException;
use App\Server;
use WebSocketUser;
use App\Helpers\Helpers;
use App\Helpers\MapHelper;
use App\Network\Packets\Packet;
use App\Network\Packets\Server\Map\SM_MAP_PLAYER_MOVE;

class CM_MAP_PLAYER_MOVE extends ClientMapPacket {

    const TYPE = 'cm_map_player_move';

    public function __construct($data) {
        parent::__construct($data);
    }

    public function getX() {
        return $this->data['x'];
    }

    public function getY() {
        return $this->data['y'];
    }

    public function getDirection() {
        return $this->data['direction'];
    }

    public function doAction(Server $server, WebSocketUser $user) {
        Helpers::$MAP_HELPER->moveCharacter($user, $this->getX(), $this->getY(), $this->getDirection());
    }
}
