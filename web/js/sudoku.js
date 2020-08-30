var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
    conn.send('Hello World!');
};

conn.onmessage = function(e) {
    console.log(e.data);
};


