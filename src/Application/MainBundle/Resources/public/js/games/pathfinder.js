Point.prototype = {};
Point.prototype.constructor = Point;
Point.prototype.r = null;
Point.prototype.c = null;
function Point(r, c) {
    this.r = r;
    this.c = c;
}

// ------------------------------------------------------------ //

// ------------------------------------------------------------ //

Queue.prototype = new Object();
Queue.prototype.constructor = Queue;

function Queue() {

}

Queue.prototype.data = [];

Queue.prototype.enqueue = function (obj) {
    this.data.push(obj);
};

Queue.prototype.dequeue = function () {
    return this.data.shift();
};

Queue.prototype.size = function () {
    return this.data.length;
};

///////////////////////////////////////////

Board.prototype = {};
Board.prototype.constructor = Board;
Board.prototype.directions = {
    'NORTH': 0,
    'WEST': 1,
    'SOUTH': 2,
    'EAST': 3
};

function Board(data, source, target) {

    this.queue = new Queue();
    this.data = data;
    this.source = [1 * source[0], 1 * source[1]];
    this.target = [1 * target[0], 1 * target[1]];

    this.data[this.source[0]][this.source[1]] = 0;

    this.queue.enqueue(new Point(this.source[0], this.source[1]));

    this.run();
    this.generatePath();

    if(this.pathExists()) {
        this.path.push(this.target[0] + '-' + this.target[1]);
    }
}

Board.prototype.data = [];
Board.prototype.source = [];
Board.prototype.target = [];
Board.prototype.path = [];
Board.prototype.empty = -1;

Board.prototype.pathExists = function () {
    return this.path.length > 0;
};

Board.prototype.run = function (queue) {

    queue = queue || this.queue;

    if (this.queue.size() > 0) {
        var src = this.queue.dequeue();
        for (var dir in this.directions) {
            var p = this.getNeighbour(src, dir)
            if (p !== null) {
                this.data[p.r][p.c] = this.data[src.r][src.c] + 1;
                this.queue.enqueue(p);
                this.run();
            }
        }
    }
};

Board.prototype.getPath = function () {
    return this.path;
}

Board.prototype.generatePath = function () {

    trg = this.target;

    while (true) {

        var val = this.data[trg[0]][trg[1]];
        var curr = new Point(trg[0], trg[1]);
        var next = null;

        next = this.getLowerNeighbour(curr);

        if (next === null) {
            return this.path.reverse();
        }

        trg = [next.r, next.c];
    }
};

Board.prototype.getLowerNeighbour = function (point, dir) {
    var val = 1000000;
    var next = null;

    for (var dir in this.directions) {

        var p = null;

        switch (dir) {
            case 'NORTH':
                if (typeof this.data[point.r - 1] !== 'undefined' && this.data[point.r - 1][point.c] < this.data[point.r][point.c]) {
                    p = new Point(point.r - 1, point.c);
                }
                break;
            case 'SOUTH':
                if (typeof this.data[point.r + 1] !== 'undefined' && this.data[point.r + 1][point.c] < this.data[point.r][point.c]) {
                    p = new Point(point.r + 1, point.c);
                }
                break;
            case 'EAST':
                if (typeof this.data[point.r][point.c + 1] !== 'undefined' && this.data[point.r][point.c + 1] < this.data[point.r][point.c]) {
                    p = new Point(point.r, point.c + 1);
                }
                break;
            case 'WEST':
                if (typeof this.data[point.r][point.c - 1] !== 'undefined' && this.data[point.r][point.c - 1] < this.data[point.r][point.c]) {
                    p = new Point(point.r, point.c - 1);
                }
                break;
        }

        if (p !== null && this.data[p.r][p.c] >= 0 && this.data[p.r][p.c] < val) {
            val = 1 * this.data[p.r][p.c];
            next = p;
        }
    }

    if (next !== null) {
        this.path.push(next.r + '-' + next.c);
    }

    return next;
};

Board.prototype.getNeighbour = function (point, dir) {

    switch (dir) {
        case 'NORTH':
            if (typeof this.data[point.r - 1] !== 'undefined' && this.data[point.r - 1][point.c] === this.empty) {
                return new Point(point.r - 1, point.c);
            }
            break;
        case 'SOUTH':
            if (typeof this.data[point.r + 1] !== 'undefined' && this.data[point.r + 1][point.c] === this.empty) {
                return new Point(point.r + 1, point.c);
            }
            break;
        case 'EAST':
            if (typeof this.data[point.r][point.c + 1] !== 'undefined' && this.data[point.r][point.c + 1] === this.empty) {
                return new Point(point.r, point.c + 1);
            }
        case 'WEST':
            if (typeof this.data[point.r][point.c - 1] !== 'undefined' && this.data[point.r][point.c - 1] === this.empty) {
                return new Point(point.r, point.c - 1);
            }
            break;
    }

    return null;
};
