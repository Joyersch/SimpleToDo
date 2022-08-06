//sends post request to given address with given data.
function sendDataTo(reciever,data, request_method ,func_responseHandle){

    var url = reciever;

    var xhr = new XMLHttpRequest();
    xhr.open(request_method, url);
    
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
    var url = "../api/v1/todos/";
    var data = JSON.stringify({
        "Text":text,
        "Category":category
    });
    return sendDataTo(url,data, "POST", func_responseHandle);
}

function redirectToCreateEntry(text, todo, func_responseHandle){
    var url = "../api/v1/entries/";
    var data = JSON.stringify({
        "LinkedToDo":todo,
        "Text":text
    });
    return sendDataTo(url,data, "POST", func_responseHandle);
}

function redirectToCreateSubEntry(text, todo, subentry, func_responseHandle){
    var url = "../api/v1/entries/";
    var data = JSON.stringify({
        "LinkedToDo":todo,
        "Text":text,
        "LinkedEntry":subentry
    });
    return sendDataTo(url,data,"POST", func_responseHandle);
}

function redirectToChecked(e,s, func_responseHandle){
    var url = "./api/v1/entries/" + e;
    var data = JSON.stringify({
        "ID":e,
        "Done":s
    });
    return sendDataTo(url,data,"PUT", func_responseHandle);
}

function redirectToUpdateEntry(text, entry, func_responseHandle){
    var url = "../api/v1/entries/" + entry;
    var data = JSON.stringify({
        "ID":entry,
        "Text":text
    });
    return sendDataTo(url,data, "PUT",func_responseHandle);
}

function redirectToDeleteToDo(todo, func_responseHandle){
    var url = "../api/v1/todos/" + todo;
    return sendDataTo(url,"","DELETE",func_responseHandle);
}

function redirectToDeleteEntry(entry, func_responseHandle){
    var url = "../api/v1/entries/" + entry;
    return sendDataTo(url,"","DELETE",func_responseHandle);
}

function reloadPage(){
    window.location.reload(false);
}

function moveToPage(dir){
    window.location.href = dir;
}
