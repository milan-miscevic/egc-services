window.onload = function() {
    fetch('/games', {
        method: 'GET',
        headers: {'Content-Type': 'application/json'},
    })
    .then(response => response.json())
    .then(data => {
        data.forEach(function(game, key) {
            addGame(game.id, game.name, game.status);
        });
    });
};

document.getElementById("game-add").onclick = async function() {
    var name = document.getElementById('game-name').value;
    var data = {name: name};

    var response = await fetch('/games/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
    });

    var data = await response.json();

    if (response.status === 200) {
        addGame(data.id, name, 'active');
    } else if (response.status === 400) {
        showErrors(data.errors, 'game');
    } else {
        //
    }
};

function addGame(id, name, status)
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
