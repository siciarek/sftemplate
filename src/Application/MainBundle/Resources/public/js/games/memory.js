var timeout = 500;
var current = [];
var symbols = [];
var board = [];
var size = 'small';

if(location.href.match(/(small|medium|big)$/) !== null) {
    size = location.href.replace(/^.*?(small|medium|big)$/, '$1');
}

//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com/array/shuffle [v1.0]
function shuffle(o){ //v1.0
    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
}

$(document).ready(function () {

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

    var sizes = {
        small: [4, 6],
        medium: [6, 10],
        big: [8, 14]
    };

    var rows = sizes[size][0];
    var cols = sizes[size][1];
    var tailsCount = rows * cols;

    var count = tailsCount / 2;
    var tails = {};
    var tailkeys = [];

    back = symbols[Math.floor(Math.random() * 10000 % symbols.length)];

    do {
        symbol = symbols[Math.floor(Math.random() * 10000 % symbols.length)];

        if ((symbol !== back) && (typeof tails[symbol] == 'undefined')) {
            tails[symbol] = 2;
            count--;
        }
    } while (count > 0);

    for (var key in tails) {
        tailkeys.push(key);
    }

    for (var r = 0; r < rows; r++) {
        board.push([]);

        for (var c = 0; c < cols; c++) {

            var kinx = Math.floor(Math.random() * 10000 % tailkeys.length);
            var tail = tailkeys[kinx];

            board[r][c] = tail;
            tails[tail]--;

            if (tails[tail] == 0) {
                tailkeys.splice(kinx, 1);
            }
        }

        board[r] = shuffle(board[r]);
    }

    board = shuffle(board);

    for(r = 0; r < rows; r++) {
        for(c = 0; c < cols; c++) {
            var icon = back;
            // icon = board[r][c]; // debug previev
            $('#board').append('<div id="card-' + r + '-' + c + '" class="memory-tail icon-' + icon + '"></div>');
        }
        $('#board').append('<div style="clear:both"></div>');
    }

    $('.memory-tail').click(function () {
        var match = $(this).attr('id').match(/^card\-(\d+)-(\d+)$/);
        var id = '#' + match[0];
        var row = match[1];
        var col = match[2];
        var symbol = board[row][col];

        if (tailsCount === 0) {
            return;
        }

        if ($(id).hasClass('success')) {
            return;
        }

        $(id).removeClass('icon-' + back).addClass('icon-' + symbol);

        if (current.length === 0 || current[0].id !== id) {
            current.push({id: id, symbol: symbol});
            $(current[0].id).addClass('success');
        }

        if (current.length === 2) {

            if (current[0].symbol !== current[1].symbol) {
                $(current[0].id).addClass('failed');
                $(current[1].id).addClass('failed');

                setTimeout(function () {
                    $(current[0].id).removeClass('success').removeClass('failed');
                    $(current[1].id).removeClass('failed');
                    $(current[0].id).removeClass('icon-' + current[0].symbol).addClass('icon-' + back);
                    $(current[1].id).removeClass('icon-' + current[1].symbol).addClass('icon-' + back);
                    current = [];
                }, timeout);
            }
            else {
                tailsCount -= 2;
                $(current[0].id).addClass('success');
                $(current[1].id).addClass('success');
                current = [];
            }
        }

        if (tailsCount === 0) {
            $('#dialog').modal('toggle');
        }
    });
});
