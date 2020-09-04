var conn = new WebSocket('ws://localhost:8080');

const events = {
    'start': 'startGameAccept',
    'move': 'eventMoveRepose',
    'topList': 'eventShowTopListResponse',
    'freeCells': 'eventFreeCells',
    'error': 'eventError'
};

conn.onopen = function(e) {
    $('.btn-action').prop('disabled', false);
};

conn.onmessage = function(e) {
    console.log(e.data);
    var event = JSON.parse(e.data);
    if (!event){
        return;
    }
    switch (event.event) {
        case events.start:
            innitBoard(event.data.game);
            break;
        case events.move:
            updateGame(event.data.cellId, event.data.value);
            break;
        case events.freeCells:
            freeCells(event.data.cells);
            break;
        case events.error:
            error(event.error);
            break;
        case events.topList:
            showTopList(event.data.topList);
            break;
        default:
            break;
    }
};

$('#btn-start').click(function (e) {
    var name = prompt("Введите имя", "");
    if (name === null || name === "") {
        return
    }
    var event = {
        name: "startGameRequest",
        data: {"name": name}
    };
    conn.send(JSON.stringify(event));
});

$('#btn-list').click(function (e) {
    var event = {
        name: 'eventShowTopListRequest',
        data:{}
    };
    conn.send(JSON.stringify(event));
    console.log(JSON.stringify(event));
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
    $('#sudoku-board').show();
    $('#topList').hide();
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


function showTopList(list) {
    var $list = $('#list_users');
    $list.html('');
    list.forEach((name, score) => {
        $list.append("<li><strong>" + name + ":</strong> " + score + "</li>")
    });
    $('#topList').show();
    $('#sudoku-board').hide();
}


function error(error) {
    $('#sudoku-board').hide();
    $('#topList').hide();
    alert(error);
}


