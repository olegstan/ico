var jackpotQuality = 0;
var jackPots = [
    {
        old:0.0,
        new:0.0
    },
    {
        old:0.0,
        new:0.0
    },
    {
        old:0.0,
        new:0.0
    }
];

Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};

function digitTransitionEnd(element)
{
    element = element.target;
    var activeDigit = parseInt(element.getAttribute("digit"));
    element.classList.add("notransition");
    for(var child in element.children)
        if(element.children.hasOwnProperty(child))
            element.children[child].innerText = (activeDigit++)%10;
    element.style.marginTop = "0px";
}

var digits = document.querySelectorAll('.jackpot-digit');
for(var digit in digits)
{
    if(digits.hasOwnProperty(digit))
        digits[digit].addEventListener("transitionend", digitTransitionEnd, true);
}

setInterval(function(){
    for(var i = 0; i < 3; ++i)
    {
        if(jackPots[i].new != jackPots[i].old)
            setJackPot(i, jackPots[i].new);
    }
}, 5000);

function setJackPot(id, value) {
	
	console.log("set jackpot");
	
    jackPots[id].old = value;
    jackPots[id].new = value;
    jackNames = ['jackpot_gold', 'jackpot_silver', 'jackpot_bronze'];

    if (jackpotQuality == 2) {
        var wasTransed = false;
        var str = parseFloat(value).toFixed(2).split('.').join('');
        str = ("0000000000" + str).slice(-11);
        var jackpotDigits = $('#' + jackNames[id] + ' .jackpot-digit');
        for (var digit = jackpotDigits.length - 1; digit >= 0; --digit) {
            var d = parseInt(str.charAt(digit));
            var dg = jackpotDigits[digit].children[0].getAttribute("digit") ?
                    parseInt(jackpotDigits[digit].children[0].getAttribute("digit")) : 0;
            if (d != dg) {
                var step = Math.abs(((d < dg) ? (d + 10) : d) - dg);
                jackpotDigits[digit].children[0].classList.remove("notransition");
                jackpotDigits[digit].children[0].style.marginTop = ((-30) * step) + "px";
                jackpotDigits[digit].children[0].setAttribute("digit", d);
            }
        }
    }
    else if(jackpotQuality == 1)
    {
        var str = parseFloat(value).toFixed(2).split('.').join('');
        str = ("0000000000" + str).slice(-19);
        var jackpotDigits = $('#' + jackNames[id] + ' .jackpot-digit');
        var digit = jackpotDigits.length - 1;
        var d = parseInt(str.charAt(digit));
        var dg = jackpotDigits[digit].children[0].getAttribute("digit") ?
                parseInt(jackpotDigits[digit].children[0].getAttribute("digit")) : 0;
        if (d != dg) {
            var step = Math.abs(((d < dg) ? (d + 10) : d) - dg);
            jackpotDigits[digit].children[0].classList.remove("notransition");
            jackpotDigits[digit].children[0].style.marginTop = ((-30) * step) + "px";
            jackpotDigits[digit].children[0].setAttribute("digit", d);
        }
        for (digit = jackpotDigits.length - 2; digit >= 0; --digit) {
            var d = parseInt(str.charAt(digit));
            var dg = jackpotDigits[digit].children[0].getAttribute("digit") ?
                    parseInt(jackpotDigits[digit].children[0].getAttribute("digit")) : 0;
            if (d != dg) {
                for(var child in jackpotDigits[digit].children[0].children)
                    if(jackpotDigits[digit].children[0].children.hasOwnProperty(child))
                        jackpotDigits[digit].children[0].children[child].innerText = (d++)%10;
                jackpotDigits[digit].children[0].setAttribute("digit", d);
            }
        }
    }
    else
    {
        var str = parseFloat(value).toFixed(3).split('.').join('');
        //console.log(str);
        str = ("0000000000" + str).slice(-9);
        //console.log(str);
        var jackpotDigits = jQuery('#' + jackNames[id] + ' .jackpot-digit');
        for (digit = jackpotDigits.length - 1; digit >= 0; --digit) {
            var d = parseInt(str.charAt(digit));
            var dg = jackpotDigits[digit].children[0].getAttribute("digit") ?
                    parseInt(jackpotDigits[digit].children[0].getAttribute("digit")) : 0;
            if (d != dg) {
                for(var child in jackpotDigits[digit].children[0].children)
                    if(jackpotDigits[digit].children[0].children.hasOwnProperty(child))
                        jackpotDigits[digit].children[0].children[child].innerText = (d++)%10;
                jackpotDigits[digit].children[0].setAttribute("digit", d);
            }
        }
    }
}
