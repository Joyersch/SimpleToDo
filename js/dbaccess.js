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

function redirectToCreateToDo(text, category, authentication, func_responseHandle){
    var url = "../api/v1/todos?authkey=" + authentication;
    var data = JSON.stringify({
        "Text":text,
        "Category":category
    });
    return sendDataTo(url,data, "POST", func_responseHandle);
}

function redirectToCreateEntry(text, todo, authentication, func_responseHandle){
    var url = "../api/v1/entries?authkey=" + authentication;
    var data = JSON.stringify({
        "LinkedToDo":todo,
        "Text":text
    });
    return sendDataTo(url,data, "POST", func_responseHandle);
}

function redirectToCreateSubEntry(text, todo, subentry, authentication, func_responseHandle){
    var url = "../api/v1/entries?authkey=" + authentication;
    var data = JSON.stringify({
        "LinkedToDo":todo,
        "Text":text,
        "LinkedEntry":subentry
    });
    return sendDataTo(url,data,"POST", func_responseHandle);
}

function redirectToChecked(id, done, authentication, func_responseHandle){
    var url = "./api/v1/entries/" + id + "?authkey=" + authentication;
    var data = JSON.stringify({
        "ID":id,
        "Done":done
    });
    return sendDataTo(url,data,"PUT", func_responseHandle);
}

function redirectToUpdateEntry(text, entry, authentication, func_responseHandle){
    var url = "../api/v1/entries/" + entry + "?authkey=" + authentication;
    var data = JSON.stringify({
        "ID":entry,
        "Text":text
    });
    return sendDataTo(url,data, "PUT",func_responseHandle);
}

function redirectToDeleteToDo(todo, authentication, func_responseHandle){
    var url = "../api/v1/todos/" + todo + "?authkey=" + authentication;
    return sendDataTo(url,"","DELETE",func_responseHandle);
}

function redirectToDeleteEntry(entry, authentication, func_responseHandle){
    var url = "../api/v1/entries/" + entry + "?authkey=" + authentication;
    return sendDataTo(url,"","DELETE",func_responseHandle);
}

function isResponseGood(response){
    switch (response) {
        case 200:
        case 201:
        case 202:
            return true;
        default:
            return false;
    }
}

function moveToPage(dir){
    window.location.href = dir;
}

function moveToBase(){
    moveToPage("..");
}

function moveToBaseIfGood(data){
    if (isResponseGood(data.target.status))
        moveToBase();
    else
        alert("an eror occured!\ncode:" + data.target.status);
}

function reloadPage(){
    window.location.reload(false);
}

function reloadPageIfGood(data){
    if (isResponseGood(data.target.status))
        reloadPage();
    else
        alert("an eror occured!\ncode:" + data.target.status);
}