var conn = new WebSocket('ws://localhost:8080');

const events = {
    'start': 'startGameAccept',
    'move': 'eventMoveRepose',
    'topList': 'eventShowTopListResponse',
    'freeCells': 'eventFreeCells'
};

conn.onopen = function(e) {
    $('.btn-action').prop('disabled', false);
};

conn.onmessage = function(e) {
    console.log(e.data);
    event = JSON.parse(e.data);
    if (event.event === events.start) {
        innitBoard(event.data.game);
        $('#sudoku-board').show();
    }

    if (event.event === events.move) {
        updateGame(event.data.cellId, event.data.value);
    }

    if (event.event === events.freeCells) {
        freeCells(event.data.cells);
    }
};

$('#btn-start').click(function (e) {
    var event = {
        name: "startGameRequest",
        data: {"name": "Andrey" + Math.random()}
    };
    conn.send(JSON.stringify(event));
});

function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 1 && n <= 9;
}

$('.cell').change(function (e) {
    if (isNormalInteger($(this).val())) {
        var event = {
            name: "eventMoveRequest",
            data: {"cellId": $(this).data('id'), "value": $(this).val()}
        };
        conn.send(JSON.stringify(event));
    } else {
        $(this).val('')
    }

});

function innitBoard(data) {
    var id = 1;
    for (i = 0; i < 9; i++) {
        for (j = 0; j < 9; j++) {
            var item = $('#cell' + id++);
            if (data[i][j]) {
                item.val(data[i][j]);
                item.prop('disabled', true);
            } else {
                item.val('');
                item.prop('disabled', false);
            }
        }
    }
}

function updateGame(id, value) {
    var item = $('#cell' + id);
    if (item.length == 1) {
        item.val(value);
        item.prop('disabled', true);
    }
}


function freeCells(cells) {
    cells.forEach((id) => {
        var item = $('#cell' + id);
        item.val('');
        item.prop('disabled', false);
    })
}


