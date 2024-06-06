function generateForm(settingsList, userSettings) {
    var form = document.getElementById("settingsForm");
    var table = document.createElement("table");
    for (var key in settingsList) {
        var row = document.createElement("tr");
        var setting = settingsList[key];
        var label = document.createElement("td");
        label.innerHTML = setting.label + ": ";
        row.appendChild(label);
        var cell = document.createElement("td");
        if (setting.type === "select") {
            var select = document.createElement("select");
            select.id = key;
            select.name = key;
            for (var option of setting.options) {
                var opt = document.createElement("option");
                opt.value = option;
                opt.innerHTML = option;
                if (userSettings[key] === option) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            }
            cell.appendChild(select);
        
        } else if (setting.type === "checkbox") {
            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.id = key;
            checkbox.name = key;
            if (userSettings[key]) {
                checkbox.checked = true;
            }
            cell.appendChild(checkbox);
        
        } else if (setting.type === "text") {
            var textinput = document.createElement("textarea");
            textinput.id = key;
            textinput.name = key;
            textinput.rows = setting.rows;
            textinput.cols = setting.cols;
            textinput.style.resize = "none";
            if (key === "tags") {
                textinput.value = normalizeTags(userSettings[key]);
            } else {
                textinput.value = userSettings[key];
            }
            cell.appendChild(textinput);
        }

        row.appendChild(cell);
        table.appendChild(row);
    }
    form.appendChild(table)
    var submit = form.appendChild(document.createElement("input"));
    submit.value = "Save Settings";
    submit.type = "submit";
}

function normalizeTags(tags) {
    if (!tags) return "";
    // A bit of magic ! Turns any combination of tags and dots, colons, semicolons, commas and spaces into a normalized tag format 
    return tags.split(/[ ,.;:]+/).filter(tag => tag.trim() !== "").join(", ");
}


// On a utilisé XHR dans ce projet, donc ajouter fetch ici ca fait tâche.
// TODO : Choisir un des deux ET RESTER SUR CE CHOIX.
// En plus fetch c'est pas bien pour la sécurité, et en plus
// Je comprends a peine le système de promesse donc ya toujours
// 304598734950 bugs avec fetch :(

function loadSettings() {
    fetch("script/settingsList.json")
        .then(response => response.json())
        .then(settingsList => {
            fetch("../users/"+document.getElementById('username').value+"/user.json")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('User settings not found');
                    }
                    return response.json();
                })
                .then(userSettings => generateForm(settingsList, userSettings))
                .catch(error => {
                    console.error('Error loading user settings 23423:', error);
                    generateForm(settingsList, "");
                });
        })
}

window.onload = loadSettings;

function saveSettings() {
    fetch("script/settingsList.json")
        .then(response => response.json())
        .then(settingsList => {
            var settings = {};
            for (var key in settingsList) {
                var setting = settingsList[key];
                if (setting.type === "select" || setting.type === "text") {
                    if (key === "tags") {
                        settings[key] = normalizeTags(document.getElementById(key).value);
                    } else {
                        settings[key] = document.getElementById(key).value;
                    }
                } else if (setting.type === "checkbox") {
                    settings[key] = document.getElementById(key).checked;
                }
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "script/saveSettings.php?target=" + encodeURIComponent(document.getElementById("username").value), true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById("saveStatus").innerText = "Settings Saved!";
                    } else {
                        document.getElementById("saveStatus").innerText = xhr.statusText;
                    }
                }
            };

            xhr.send(JSON.stringify(settings));
        })
}