//sends post request to given address with given data.
function sendDataTo(reciever,data, func_responseHandle){

    var url = reciever;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    
    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");
    if (Isset(func_responseHandle)|| func_responseHandle === false){
        xhr.onreadystatechange = func_responseHandle;
    }

    xhr.send(data);

    return true;
}

//checks if x is not undefined
function Isset(x){
return (typeof x !== "undefined") 
}

function redirectToCreateToDo(text, category, func_responseHandle){
    var url = "./interface/create/todo.php/";
    var data = JSON.stringify({
        Text:text,
        Category:category
    });
    return sendDataTo(url,data, func_responseHandle);
}

function redirectToCreateSubEntry(text, todo, subentry, func_responseHandle){
    var url = "./interface/create/subentry.php/";
    var data = JSON.stringify({
        ToDo:todo,
        Text:text,
        SubEntry:subentry
    });
    return sendDataTo(url,data, func_responseHandle);
}

function redirectToCreateEntry(text, todo, func_responseHandle){
    var url = "./interface/create/entry.php/";
    var data = JSON.stringify({
        ToDo:todo,
        Text:text
    });
    return sendDataTo(url,data, func_responseHandle);
}

function redirectToChecked(e,s, func_responseHandle){
    var url = "./interface/update/checked.php/";
    var data = JSON.stringify({
        Entry:e,
        State:s
    });
    return sendDataTo(url,data, func_responseHandle);
}

function redirectToUpdateEntry(text, entry, func_responseHandle){
    var url = "./interface/update/entry.php/";
    var data = JSON.stringify({
        Text:text,
        Id:entry
    });
    return sendDataTo(url,data,func_responseHandle);
}

function redirectToDeleteToDo(todo, func_responseHandle){
    var url = "./interface/delete/todo.php/";
    var data = JSON.stringify({
        ToDo:todo
    });
    return sendDataTo(url,data,func_responseHandle);
}

function redirectToDeleteEntry(entry, func_responseHandle){
    var url = "./interface/delete/entry.php/";
    var data = JSON.stringify({
        Entry:entry
    });
    return sendDataTo(url,data,func_responseHandle);
}

function reloadPage(){
    window.location.reload(false);
}

function moveToPage(dir){
    window.location.href = dir;
}
