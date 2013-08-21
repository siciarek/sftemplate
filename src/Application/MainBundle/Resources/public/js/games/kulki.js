var rows = 8;
var cols = 16;
var level = 3;
var additional = 3;

var source = [];
var target = [];
var icon = 'circle';
var colors = [
    'red', 'green', 'blue', 'orange', 'fuchsia', 'silver'
];
var symbols = [];
var board = [];
var tempBoard = [];
var findingPath = false;
var inter = null;
var stack = [null];

function array_keys(o) {
    var keys = [];

    for(var key in o) {
        keys.push(key);
    }

    return keys;
}

function loadData() {
    $.ajax({
        async: false,
        url: '/css/font-awesome.css',
        success: function (data) {
            var rx = new RegExp("\\.icon\\-(\\w+(\\-\\w+)*):before\\s*\\{", "g");
            var match = null;
            var start = false;
            while (match = rx.exec(data)) {
                if (match[1] === 'glass') {
                    start = true;
                }
                if (start === true) {
                    symbols.push(match[1]);
                }
            }
        }
    });
}

function reset() {

    $('#board').children().remove();

    board = [];

    for (var r = 0; r < rows; r++) {
        board[r] = [];
        $('#board').append('<div id="kulki-row-' + r + '"></div>');

        for (var c = 0; c < cols; c++) {
            board[r][c] = null;
            $('#kulki-row-' + r).append('<div id="kulki-cell-' + r + '-' + c + '"><i class="icon-blank"></i></div>');
        }
    }

    findingPath = false;
}

function empty(r, c) {
    return board[r][c] === null;
}

function fill(r, c) {
    var color = colors[Math.floor(Math.random() * 10000 % colors.length)];
    board[r][c] = color;
}

function updateBoard() {
    for (var r = 0; r < rows; r++) {
        for (var c = 0; c < cols; c++) {
            $('#kulki-cell-' + r + '-' + c + ' i').removeClass('icon-circle');
            $('#kulki-cell-' + r + '-' + c + ' i').addClass('icon-blank');
            if (board[r][c] !== null) {
                $('#kulki-cell-' + r + '-' + c + ' i').removeClass('icon-blank');
                $('#kulki-cell-' + r + '-' + c + ' i').addClass('icon-circle');
                $('#kulki-cell-' + r + '-' + c + ' i').addClass(board[r][c]);
            }
        }
    }
    findingPath = false;
}

function findPath(board, src, trg) {
    $('#temp').empty();
    tempBoard = [];

    for (var r = 0; r < board.length; r++) {
        tempBoard[r] = [];
        for (var c = 0; c < board[0].length; c++) {
            var val = board[r][c] !== null ? -9 : -1;
            tempBoard[r][c] = val;
        }
    }

//    $('#temp').html([tempBoard.toSource(), src, trg]);

    var pth = [];
    var brd = new Board(tempBoard, src, trg);

    if (brd.pathExists() === true) {
        pth = brd.getPath();
        source = src;
        target = trg;
    }
    else {
        findingPath = false;
        source = [];
        target = [];
        return;
    }

    $('#kulki-cell-' + target[0] + '-' + target[1] + ' i').removeAttr('class');
    $('#kulki-cell-' + target[0] + '-' + target[1] + ' i').addClass('icon-circle');

    inter = setInterval(function () {

        var curr = pth.shift();
        var cls = $('#kulki-cell-' + curr + ' i').attr('class');
        var next = pth[0];

        if (typeof next !== 'undefined') {
            $('#kulki-cell-' + next + ' i').removeAttr('class');
            $('#kulki-cell-' + next + ' i').attr('class', cls);
            $('#kulki-cell-' + curr + ' i').addClass('icon-blank');
        }
        else {
            board[target[0]][target[1]] = board[source[0]][source[1]];
            board[source[0]][source[1]] = null;

            if (updatePoints() === 0) {
                setAdditional();
                updatePoints();
            }

            updateBoard();
            source = [];
            target = [];

            findingPath = false;
            clearInterval(inter);
        }
    }, 150);
}

function setAdditional() {
    for (var i = 0; i < additional;) {
        r = Math.floor(Math.random() * 10000 % rows);
        c = Math.floor(Math.random() * 10000 % cols);

        if (empty(r, c)) {
            fill(r, c);
            i++;
        }
    }
}


function updatePointsTopDown() {
    var ret = 0;

    for (var c = 0; c < cols; c++) {
        var ver = {};
        var val = null;
        var temp = null;

        for (var r = 0; r < rows; r++) {
            val = board[r][c];

            if (val === null) {
                ver[temp] = [];
                temp = null;
                continue;
            }

            if (typeof ver[val] === 'undefined') {
                ver[val] = [];
            }

            if (temp !== val) {
                ver[val] = [];
                temp = null;
            }

            ver[val].push([r, c]);

            temp = val;
        }

        ret += updateBoardPoints(ver);
    }
    return ret;
}

function updateBoardPoints(ver) {
    var ret = 0;

    if(ver.toSource() != '({})') {
        $('#temp').html($('#temp').html() + ' ' + ver.toSource());
    }

    for (var key in ver) {
        if (ver[key].length >= level) {
            for (var i = 0; i < ver[key].length; i++) {
                ret++;
                var r = ver[key][i][0];
                var c = ver[key][i][1];
                board[r][c] = null;
            }
        }
    }

    return 1 * ret;
}

function updatePointsLeftRight() {
    var ret = 0;

    for (var r = 0; r < rows; r++) {
        var result = {};

        for (var c = 1; c < cols; c++) {
            var r = {};

            for(var i = 0; i < limit; i++) {
                val = board[r][c + 1];
                r[val] = 1;
            }
        }
    }

    return ret;
}

function updatePoints() {

    var ret = 0;


    ret += updatePointsLeftRight();
//    ret += updatePointsTopDown();

//    updateScore(ret);
    return ret;
}

function updateScore(val) {
    var current = 1 * $('#score').html();
    var score = current + val;
    $('#score').html(score);
}

$(document).ready(function () {

    reset();
    loadData();
    updateBoard();

    $('#board').delegate('div[id^="kulki-cell-"]', 'click', function () {

        if (findingPath === true) {
            return;
        }

        temp = $(this).attr('id').replace(/^\D+(\d+)\-(\d+)$/, '$1;$2').split(';');

        var r = temp[0];
        var c = temp[1];

        if (empty(r, c)) {

            if (source.length === 0) {
                fill(r, c);
                if (updatePoints() === 0) {
                    setAdditional();
                    updatePoints();
                }
                updateBoard();
            }
            else {
                target = [r, c];
                findingPath = true;
                findPath(board, source, target);
            }
        }
        else {
            source = [r, c];
        }
    });
});
