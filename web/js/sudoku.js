var conn = new WebSocket('ws://localhost:8080');
var userName = "";
var start = false;

const events = {
    'start': 'startGameAccept',
    'move': 'eventMoveRepose',
    'topList': 'eventShowTopListResponse',
    'freeCells': 'eventFreeCells',
    'finish': 'eventFinish',
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
        case events.finish:
            finish();
            break;
        default:
            break;
    }
};

$('#btn-start').click(function (e) {
    if (start) {
        $('#sudoku-board').show();
        $('#topList').hide();
        $(this).hide();
        return;
    }

    if (!userName) {
        userName = prompt("Введите имя");
    }
    if (!userName) {
        return;
    }
    var event = {
        name: "startGameRequest",
        data: {"name": userName}
    };
    conn.send(JSON.stringify(event));
    $(this).hide();
});

$('#btn-list').click(function (e) {
    var event = {
        name: 'eventShowTopListRequest',
        data:{}
    };
    conn.send(JSON.stringify(event));
    console.log(JSON.stringify(event));
    $('#btn-start').show();
});

function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 1 && n <= 9;
}

$('.cell').change(function (e) {
    if (isNormalInteger($(this).val()) || $(this).val() === "") {
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
    start = true;
    $('#sudoku-board').show();
    $('#topList').hide();
}

function updateGame(id, value) {
    var item = $('#cell' + id);
    if (item.length == 1) {
        if (!value) {
            item.val("");
            item.prop('disabled', false);
        } else {
            item.val(value);
            item.prop('disabled', true);
        }
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
    for (var prop in list) {
        if (list.hasOwnProperty(prop)) {
            $list.append("<li><strong>" + prop + ":</strong> " + list[prop] + "</li>")
        }
    }

    $('#topList').show();
    $('#sudoku-board').hide();
}


function error(error) {
    $('#sudoku-board').hide();
    $('#topList').hide();
    $('#btn-start').show();
    userName = "";
    alert(error);
}


function finish() {
    $('#sudoku-board').hide();
    $('#topList').hide();
    userName = "";
    $('#btn-start').show();
    start = false;
    alert('The game over!');
}

