jQuery(document).ready(function($){
    var socket = new WebSocket("wss://api.spin2spin.com:443/websocket");

    socket.onopen = function (event) {
        console.log("open");
        return;
    };

    socket.onmessage = function (event) {
		console.log("message");
        if(event){
            var json = JSON.parse(event.data);
            switch (json.action){
                case "update_jackpot":
                    console.log("update_jackpot");
                    for (var jackpot in json.data){
                        if(!json.data.hasOwnProperty(jackpot))
                            continue;
                        for(var i = 0; i < json.data[0].length; ++i)
                            jackPots[i].new = json.data[0][i];
                    }
                    break;
                case "update_credits":
					console.log("update_credits");
                    if(json.hash === window.userHash){
                        $('#credits').text(json.credits.toFixed(3));
                    }
                    break;
				case "jackpot":
					console.log("win");
					if (json.hash === window.userHash){
					
						var jackpot_name = "";
						
						switch(json.jackpot_name){
							case 'gold_jackpot':
								jackpot_name = 'Gold Jackpot';
								break;
							case 'silver_jackpot':
								jackpot_name = 'Silver Jackpot';
								break;
							case 'bronze_jackpot':
								jackpot_name = 'Bronze Jackpot';
								break;
							default:
								jackpot_name = 'Jackpot';
								break;
						}
						$('#JackpotModalBodyDiv').text('Congratulations! You have won ' + jackpot_name + '! Your credits has increased for ' + json.win + ' mBTC!');
						$('#JackpotModal').modal({show:true});
					}
					break;
            }
        }
        return;
    };
    socket.onclose = function (event) {
		console.log("close");
        var id = window.setInterval(
        function(){
            self.socket = new WebSocket("wss://api.spin2spin.com:443/websocket");
            window.clearInterval(id);
        }, 
        5000);
    };
    socket.onerror = function (event) {
		console.log("error");
        return;
    };
});
