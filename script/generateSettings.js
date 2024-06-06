function generateForm(settingsList, userSettings) {
    var form = document.getElementById("settingsForm");
    for (var key in settingsList) {
        var setting = settingsList[key];
        var label = document.createElement("label");
        label.innerHTML = setting.label + ": ";
        form.appendChild(label);

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
            form.appendChild(select);
        
        } else if (setting.type === "checkbox") {
            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.id = key;
            checkbox.name = key;
            if (userSettings[key]) {
                checkbox.checked = true;
            }
            form.appendChild(checkbox);
        
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
            form.appendChild(textinput);
        }

        form.appendChild(document.createElement("br"));
    }
    var submit = form.appendChild(document.createElement("input"));
    submit.value = "Save Settings";
    submit.type = "submit";
}

function normalizeTags(tags) {
    if (!tags) return "";
    // A bit of magic ! Turns any combination of tags and dots, colons, semicolons, commas and spaces into a normalized tag format 
    return tags.split(/[ ,.;:]+/).filter(tag => tag.trim() !== "").join(", ");
}

function loadSettings() {
    fetch("script/settingsList.json")
        .then(response => response.json())
        .then(settingsList => {
            fetch("../users/${document.getElementById('username').value}/user.json")
                .then(response => response.json())
                .then(userSettings => generateForm(settingsList, userSettings))
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
            xhr.open("POST", "script/saveSettings.php", true);
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
<<<<<<< HEAD
=======

function getSavedSettings() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "script/getSettings.php", true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var savedSettings = JSON.parse(xhr.responseText);
                applySavedSettings(savedSettings);
            } else {
                console.error("Failed to fetch saved settings: " + xhr.statusText);
            }
        }
    };
    xhr.send();
}

function applySavedSettings(savedSettings) {
    for (var key in savedSettings) {
        var setting = savedSettings[key];
        if (settingsList[key]) {
            if (settingsList[key].type === "select" || settingsList[key].type === "text") {
                document.getElementById(key).value = setting;
            } else if (settingsList[key].type === "checkbox") {
                document.getElementById(key).checked = setting;
            }
        }
    }
}

window.onload = function() {
    generateForm();
    getSavedSettings();
};
>>>>>>> af96c5c2d55c7d819e60a73b3347f9212b1a7003
