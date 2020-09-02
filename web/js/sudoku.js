var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    var event = {
        name: "startGameRequest",
        data: {"name": "Andrey" + Math.random()}
    };
    conn.send(JSON.stringify(event));
};

conn.onmessage = function(e) {
    console.log(e.data);
    var event = {
        name: "eventMoveRequest",
        data: {"cellId": Math.floor(Math.random() * 81), "value": 9}
    };
    conn.send(JSON.stringify(event));
};


