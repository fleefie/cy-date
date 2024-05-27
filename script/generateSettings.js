var settingsList = {
    theme: {
        label: "Theme",
        type: "select",
        options: ["light", "dark"]
    },
    notifications: {
        label: "Enable Notifications",
        type: "checkbox"
    },
    fontSize: {
        label: "Font Size",
        type: "select",
        options: ["small", "medium", "large"]
    },
    description: {
        label: "Description",
        type: "text",
        cols: "80",
        rows: "10"
    },
    tags: {
        label: "Tags",
        type: "text",
        cols: "30",
        rows: "30"
    }
};

function generateForm() {
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
            for (var option in setting.options) {
                var opt = document.createElement("option");
                opt.value = setting.options[option];
                opt.innerHTML = setting.options[option];
                select.appendChild(opt);
            }
            form.appendChild(select);
        
        } else if (setting.type === "checkbox") {
            var checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.id = key;
            checkbox.name = key;
            form.appendChild(checkbox);
        
        } else if (setting.type === "text") {
            var textinput = document.createElement("textarea");
            textinput.id = key;
            textinput.name = key;
            textinput.rows = setting.rows;
            textinput.cols = setting.cols;
            textinput.style.resize = "none";
            form.appendChild(textinput);
        }

        form.appendChild(document.createElement("br"));
    }
    var submit = form.appendChild(document.createElement("input"));
    submit.value = "Save Settings";
    submit.type = "submit";

}

function saveSettings() {
    var settings = {};
    for (var key in settingsList) {
        var setting = settingsList[key];
        if (setting.type === "select" || setting.type === "text") {
            settings[key] = document.getElementById(key).value;
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
}

window.onload = generateForm;