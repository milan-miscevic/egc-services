window.onload = function() {
    reloadGames();
};

document.getElementById("game-add").onclick = async function() {
    cleanErrors();

    var name = document.getElementById('game-name').value;
    var data = {name: name};

    var response = await fetch('/games/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
    });

    var data = await response.json();

    if (response.status === 200) {
        reloadGames();
    } else if (response.status === 400) {
        showErrors(data.errors, 'game');
    } else {
        //
    }
};

document.getElementById("army-add").onclick = async function() {
    cleanErrors();

    var name = document.getElementById('army-name').value;
    var data = {
        name: name,
        units: document.getElementById('army-units').value,
        strategy: document.getElementById('army-strategy').value,
        gameid: document.getElementById('army-gameid').value,
    };

    var response = await fetch('/armies/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
    });

    var data = await response.json();

    if (response.status === 200) {
        reloadGames();
    } else if (response.status === 400) {
        showErrors(data.errors, 'army');
    } else {
        //
    }
};

function reloadGames()
{
    document.getElementById("game-list").innerHTML = '';
    document.getElementById("army-gameid").innerHTML = '';

    fetch('/games', {
        method: 'GET',
        headers: {'Content-Type': 'application/json'},
    })
    .then(response => response.json())
    .then(data => {
        data.forEach(function(game, key) {
            addGameToTable(game.id, game.name, game.status);
            addGameToSelect("army-gameid", game.id, game.name, game.status);
            addGameToSelect("simulator-game", game.id, game.name, game.status);
        });
    });
}

function addGameToTable(id, name, status)
{
    var table = document.getElementById("game-list");

    var row = document.createElement("tr");
    table.appendChild(row);

    var cell = document.createElement("td");
    cell.appendChild(document.createTextNode(id + '.'));
    row.appendChild(cell);

    var cell = document.createElement("td");
    cell.appendChild(document.createTextNode(name + ' (' + status + ')'));
    row.appendChild(cell);
}

function addGameToSelect(parent, id, name, status)
{
    if (status != 'active') {
        return;
    }

    var select = document.getElementById(parent);

    var option = document.createElement("option");
    option.setAttribute('value', id);
    option.appendChild(document.createTextNode(name));
    select.appendChild(option);
}

function showErrors(errors, prefix)
{
    for (const field in errors) {
        var messages = [];

        for (const message in errors[field]) {
            messages.push(errors[field][message]);
        }

        document.getElementById(prefix + '-' + field + '-error').innerText = messages.join(' ');
    }
}

function cleanErrors()
{
    document.querySelector('span[id$="error"]').innerText = '';
}

document.getElementById("simulator-run").onclick = async function() {
    var data = {gameid: document.getElementById('simulator-game').value};

    fetch('/simulator', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        var rounds = document.getElementById("rounds");
        rounds.append(data.message);

        var br = document.createElement("br");
        rounds.appendChild(br);
    });
};
