let hasAuthFrame = false;

export default class APIHandler {
	
	API_URI = process.env.REACT_APP_API_URL;
	xhttp = null;

	constructor() {
		this.xhttp = new XMLHttpRequest();
	}

	push = (action, method, payload=null, callback=function(){}, headers=false, async=true) => {
		method = method.toUpperCase();
		this.xhttp = new XMLHttpRequest();
		this.xhttp.onreadystatechange = function() {
			if (this.readyState === 4) {
				var response = "";					
				var authInterval = null;
				if(this.status != 401) {
					if(this.responseText != "")
						var response = JSON.parse(this.responseText);
					callback({status: this.status, data: response});
				} else if(!hasAuthFrame) {
					document.querySelector('#auth-modal').classList.remove('hide');
					var ifrm = document.createElement('iframe');
					ifrm.setAttribute('src', uri);
					ifrm.classList.add('iframe-full');
					var authDiv = document.querySelector('#auth-div');
					authDiv.appendChild(ifrm);
					hasAuthFrame = true;
					authInterval = setInterval(function() {
						console.log(ifrm.contentWindow.document.contentType);
						if(ifrm.contentWindow.document.contentType == 'application/json') {
							console.log("authenticated");
							clearInterval(authInterval);
							authInterval = null;
							window.location.reload();
						}
					}, 1000);
				}
			}
		};

		var getQueryString = "";
		if(method == "GET" && payload) {
			for(var i in payload) {
				getQueryString += i+"="+payload[i]+"&";
			}
			getQueryString = "?"+getQueryString;
		}

		var uri = (action.indexOf('http')>-1) ? action : this.API_URI + action + getQueryString;

		this.xhttp.open(method, uri, async);
		if(headers != false) {
			for(var i in headers) {
				this.xhttp.setRequestHeader(i, headers[i]);
			}
		}
		if(method != "GET")
			this.xhttp.setRequestHeader("Content-Type", "application/json");

		// console.dir(payload);
		if(method != "GET" && payload)
			this.xhttp.send(JSON.stringify(payload));
		else
			this.xhttp.send();
	}

	cancel = () => {
		if(this.xhttp)
			this.xhttp.abort();
	}
}